<?php

namespace App\Services\User;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\{
    Hash,
    Validator
};
use App\Models\{
    Role,
    User
};
use App\Traits\GlobalTrait;

class UserService
{
    /**
     * @var GlobalTrait
     */
    use GlobalTrait;

    /**
     * UserService login
     * @param  Request $request
     * @return Response
     */
    public function login ($request): Response
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response([
                'errors' => $validator->errors()->all()
            ], 400);
        }

        $user = User::where('email', $request->email)->with([
            'role' => function ($query) {
                $query->select('id', 'name', 'type');
            },
            'userDetail' => function ($query) {
                $query->select('id', 'user_id', 'full_name');
            }
        ])
        ->first();

        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $token = ($user->role->type != 'user') ? $user->createToken('C Magazine Admin')->accessToken : $user->createToken('C Magazine User')->accessToken;

                $this->generateLog($user, "successfully logged in.");

                return response([
                    'token' => $token,
                    'user' => $user
                ]);
            } else {
                return response([
                    'errors' => [
                        'Wrong password. Please try again.'
                    ]
                ], 403);
            }
        } else {
            return response([
                'errors' => [
                    'User not found. Please try again.'
                ]
            ], 404);
        }
    }

    /**
     * Check user token
     * @param  Request $request
     * @return Response
     */
    public function checkToken ($request): Response
    {
        if ($request->user()) {
            $user = $this->getAuthenticatedUser($request->user());
            return response([
                'user' => $user
            ]);
        } else {
            return response([
                'errors' => [
                    'Invalid token. Who are you?'
                ]
            ], 403);
        }
    }

    /**
     * UserService logout
     * @param  Request $request
     * @return Response
     */
    public function logout ($request): Response
    {
        if ($request->user()) {
            $user = $this->getAuthenticatedUser($request->user());
            $token = $request->user()->token();
            $token->revoke();
            $this->generateLog($user, "successfully logged out.");
        }

        return response([
            'message' => 'User logged out'
        ]);
    }

    /**
     * UserService index
     * @param  Request $request
     * @return Response
     */
    public function index ($request): Response
    {
        $users = User::select('id', 'email', 'created_at', 'role_id')
        ->when($request->filled('role_type'), function ($query) use ($request) {
            $query->whereHas('role', function ($query) use ($request) {
                $query->whereIn('type', [$request->role_type]);
            });
        })
        ->with([
            'userDetail' => function ($query) {
                $query->select('id', 'user_id', 'full_name', 'slug', 'contact_number');
            },
            'role' => function ($query) {
                $query->select('id', 'name');
            }
        ])
        ->orderBy('created_at')
        ->when($request->filled('all'), function ($query) {
            return $query->get();
         }, function ($query) {
            return $query->paginate(20);
         });

        return response([
            'records' => $users
        ]);
    }

    /**
     * UserService store
     * @param  Request $request
     * @return Response
     */
    public function store ($request): Response
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'contact_number' => 'sometimes',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
            'role_id' => 'required',
            'enabled' => 'required'
        ]);

        if ($validator->fails()) {
            return response([
                'errors' => $validator->errors()->all()
            ], 400);
        }

        $role = Role::find($request->role_id);

        if (!$role) {
            return response([
                'errors' => [
                    'Invalid role id'
                ]
            ], 400);
        }

        $user = User::create([
            'email' => $request->email,
            'password' => $request->password,
            'role_id' => $request->role_id,
            'enabled' => $request->enabled
        ]);

        $full_name = sprintf('%s %s',
            $request->first_name,
            $request->last_name
        );

        $user->userDetail()->create([
            'member_id' => str_random(20),
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'full_name' => $full_name,
            'contact_number' => $request->contact_number,
            'slug' =>  $this->slugify($full_name, 'UserDetail')
        ]);

        if ($request->filled('child_id') && count($request->child_id) > 0) {
            for ($i = 0; $i < count($request->child_id); $i++) {
                $user->levels()->create([
                    'child_id' => $request->child_id[$i]
                ]);
            }
        }

        $this->generateLog($request->user(), "added this user ({$user->id}).");

        return response([
            'record' => $user
        ]);
    }

    /**
     * UserService show
     * @param  User $user
     * @param  Request $request
     * @return Response
     */
    public function show ($user, $request): Response
    {
        $this->generateLog($request->user(), "viewed this user ({$user->id}).");

        $user = User::where('id', $user->id)
        ->with([
            'role',
            'userDetail',
            'levels.user.userDetail'
        ])
        ->first();

        return response([
            'record' => $user
        ]);
    }

    /**
     * UserService update
     * @param  User $user
     * @param  Request $request
     * @return Response
     */
    public function update ($user, $request): Response
    {
        $user = User::where('id', $user->id)
        ->with([
            'userDetail'
        ])
        ->first();

        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'contact_number' => 'sometimes',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'old_password' => 'sometimes',
            'password' => 'sometimes|confirmed',
            'role_id' => 'required',
            'enabled' => 'required'
        ]);

        if ($validator->fails()) {
            return response([
                'errors' => $validator->errors()->all()
            ], 400);
        }

        $role = Role::find($request->role_id);

        if (!$role) {
            return response([
                'errors' => [
                    'Invalid role id'
                ]
            ], 400);
        }

        if ($request->password && $request->old_password) {
            if (Hash::check($request->old_password, $user->password)) {
                $user->update([
                    'password' => $request->password
                ]);
            } else {
                return response([
                    'errors' => [
                        "The old password you entered is invalid"
                    ]
                ], 400);
            }
        }

        $user->update([
            'email' => $request->email,
            'role_id' => $request->role_id,
            'enabled' => $request->enabled
        ]);

        $full_name = sprintf('%s %s',
            $request->first_name,
            $request->last_name
        );

        $user->userDetail()->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'full_name' => $full_name,
            'contact_number' => $request->contact_number,
            'slug' =>  $this->slugify($full_name, 'UserDetail', $user->userDetail->id)
        ]);

        if ($request->filled('child_id') && count($request->child_id) > 0) {
            $user->levels()->forceDelete();
            for ($i = 0; $i < count($request->child_id); $i++) {
                $user->levels()->create([
                    'child_id' => $request->child_id[$i]
                ]);
            }
        }

        $this->generateLog($request->user(), "updated this user ({$user->id}).");

        return response([
            'record' => $user
        ]);
    }

    /**
     * UserService destroy
     * @param  User $user
     * @param  Request $request
     * @return Response
     */
    public function destroy ($user, $request): Response
    {
        $user->delete();

        $this->generateLog($request->user(), "deleted this user ({$user->id}).");

        return response([
            'record' => 'User deleted'
        ]);
    }
}
