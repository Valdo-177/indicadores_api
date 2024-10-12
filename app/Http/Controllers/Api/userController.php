<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Indicador;
use App\Models\Users;
use Illuminate\Http\Request;


class userController extends Controller
{

    public function getColorCódigo(): string
{
    return ColorCódigo::where('indicador_id', $this->id)->first()->codigo ?? 'N/A';
}

public function index()
{
    $indicadores = Indicador::all();

    if ($indicadores->isEmpty()) {
        return response()->json([
            'message' => 'No hay indicadores',
            'status' => 404
        ]);
    }

    return response()->json([
        'message' => 'Datos obtenidos correctamente',
        'data' => $indicadores,
        'status' => 200
    ]);
}


    

    // public function index()
    // {
    //     $users = Users::all();

    //     if ($users->isEmpty()) {
    //         return response()->json([
    //             'message' => 'No hay usuarios',
    //             'status' => 404
    //         ]);
    //     }

    //     return response()->json([
    //         'message' => 'Todo salió correctamente',
    //         'data' => $users,
    //         'status' => 200
    //     ]);
    // }

    // public function store(Request $request)
// {
//     $validated = $request->validate([
//         'name' => 'required|max:255',
//         'email' => 'required|email|unique:users',
//         'phone' => 'required|digits:10',
//         'language' => 'required|in:English,Spanish,French'
//     ]);

    //     // print($validated);

    //     $student = User::create([
//         'name' => $validated['name'],
//         'email' => $validated['email'],
//         'phone' => $validated['phone'],
//         'language' => $validated['language']
//     ]);

    //     if (!$student) {
//         return response()->json(['message' => 'Error al crear el usuario', 'status' => 500], 500);
//     }

    //     return response()->json(['student' => $student, 'status' => 201], 201);
// }


    // }

    // public function store(Request $request)
// {
//     return response()->json(['student' => $request->name, 'status' => 201], 201);
// }

    // public function store(Request $request)
// {
//     $data = [
//         'name' => $request->name ?? '',
//         'Phone' => $request->Phone ?? '',
//         'languaje' => bcrypt($request->languaje ?? ''),
//     ];

    //     try {
//         $newUser = Users::create($data);

    //         return response()->json([
//             'message' => 'Usuario creado con éxito',
//             'data' => [
//                 'id' => $newUser->id,
//                 'name' => $newUser->name,
//                 'Phone' => $newUser->Phone,
//                 'languaje' => $newUser->languaje,
//             ],
//             'status' => 201
//         ], 201);
//     } catch (\Exception $e) {
//         return response()->json([
//             'error' => 'Ocurrió un error al crear el usuario',
//             'details' => $e->getMessage(),
//             'status' => 500
//         ], 500);
//     }
// }


    public function store(Request $request)
    {
        $validations = [
            'name' => [
                'required' => true,
                'min_length' => 2,
                'max_length' => 255,
                'is_valid_name' => true
            ],
            'Phone' => [
                'required' => true,
                'phone_format' => true
            ],
            'languaje' => [
                'required' => true,
                'allowed_languages' => true
            ],
        ];

        $errors = [];

        foreach ($validations as $field => $rules) {
            foreach ($rules as $rule => $condition) {
                switch ($rule) {
                    case 'required':
                        if (!$request->has($field)) {
                            $errors[] = "El campo {$field} es requerido.";
                        }
                        break;
                    case 'min_length':
                        if (strlen($request->$field) < $condition) {
                            $errors[] = "El campo {$field} debe contener al menos {$condition} caracteres.";
                        }
                        break;
                    case 'max_length':
                        if (strlen($request->$field) > $condition) {
                            $errors[] = "El campo {$field} no debe exceder {$condition} caracteres.";
                        }
                        break;
                    case 'is_valid_name':
                        if (!preg_match('/^[a-zA-Z\s]+$/', $request->$field)) {
                            $errors[] = "El nombre debe contener solo letras y espacios.";
                        }
                        break;
                    case 'phone_format':
                        if (!preg_match('/^(\+?[0-9]{1,3}[-. ]?[0-9]{3}[-. ]?[0-9]{3}[-. ]?[0-9]{4}|[0-9]+)$/', $request->$field)) {
                            $errors[] = "Por favor, ingrese un número telefónico válido.";
                        }
                        break;
                    case 'allowed_languages':
                        $allowedLanguages = ['English', 'Spanish', 'French'];
                        if (!in_array($request->$field, $allowedLanguages)) {
                            $errors[] = "Solo se aceptan idiomas: English, Spanish, French.";
                        }
                        break;
                    case 'min_length':
                        if (strlen($request->$field) < $condition) {
                            $errors[] = "La contraseña debe contener al menos {$condition} caracteres.";
                        }
                        break;
                    case 'matches_confirmation':
                        if ($request->password !== $request->confirm_password) {
                            $errors[] = "Las contraseñas no coinciden.";
                        }
                        break;
                }
            }
        }

        if (count($errors) > 0) {
            return response()->json([
                'error' => 'Los siguientes campos tienen errores:',
                'details' => $errors,
                'status' => 422
            ], 422);
        }

        $data = [
            'name' => $request->name,
            'Phone' => $request->Phone,
            'languaje' => $request->languaje,
            'password' => bcrypt($request->password),
        ];

        try {
            $newUser = Users::create($data);

            return response()->json([
                'message' => 'Usuario creado con éxito',
                'data' => [
                    'id' => $newUser->id,
                    'name' => $newUser->name,
                    'Phone' => $newUser->Phone,
                    'languaje' => $newUser->languaje,
                ],
                'status' => 201
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Ocurrió un error al crear el usuario',
                'details' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }



    public function show($id)
    {
        $student = Users::find($id);

        if (!$student) {
            $data = [
                'message' => 'Usuario no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $data = [
            'student' => $student,
            'status' => 200
        ];

        return response()->json($data, 200);
    }


    public function destroy($id)
    {
        $student = Users::find($id);

        if (!$student) {
            $data = [
                'message' => 'Usuario no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $student->delete();

        $data = [
            'message' => 'Usuario eliminado',
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function update(Request $request, $userId)
{
    $validations = [
        'name' => [
            'required' => true,
            'min_length' => 2,
            'max_length' => 255,
            'is_valid_name' => true
        ],
        'Phone' => [
            'required' => true,
            'phone_format' => true
        ],
        'languaje' => [
            'required' => true,
            'allowed_languages' => true
        ],
    ];

    $errors = [];

    foreach ($validations as $field => $rules) {
        foreach ($rules as $rule => $condition) {
            switch ($rule) {
                case 'required':
                    if (!$request->has($field)) {
                        $errors[] = "El campo {$field} es requerido.";
                    }
                    break;
                case 'min_length':
                    if (strlen($request->$field) < $condition) {
                        $errors[] = "El campo {$field} debe contener al menos {$condition} caracteres.";
                    }
                    break;
                case 'max_length':
                    if (strlen($request->$field) > $condition) {
                        $errors[] = "El campo {$field} no debe exceder {$condition} caracteres.";
                    }
                    break;
                case 'is_valid_name':
                    if (!preg_match('/^[a-zA-Z\s]+$/', $request->$field)) {
                        $errors[] = "El nombre debe contener solo letras y espacios.";
                    }
                    break;
                case 'phone_format':
                    if (!preg_match('/^(\+?[0-9]{1,3}[-. ]?[0-9]{3}[-. ]?[0-9]{3}[-. ]?[0-9]{4}|[0-9]+)$/', $request->$field)) {
                        $errors[] = "Por favor, ingrese un número telefónico válido.";
                    }
                    break;
                case 'allowed_languages':
                    $allowedLanguages = ['English', 'Spanish', 'French'];
                    if (!in_array($request->$field, $allowedLanguages)) {
                        $errors[] = "Solo se aceptan idiomas: English, Spanish, French.";
                    }
                    break;
                case 'min_length':
                    if ($rule === 'min_length' && strlen($request->$field) < $condition) {
                        $errors[] = "El campo {$field} debe contener al menos {$condition} caracteres.";
                    }
                    break;
                case 'matches_confirmation':
                    if ($request->has('password') && $request->password !== $request->confirm_password) {
                        $errors[] = "Las contraseñas no coinciden.";
                    }
                    break;
            }
        }
    }

    if (count($errors) > 0) {
        return response()->json([
            'error' => 'Los siguientes campos tienen errores:',
            'details' => $errors,
            'status' => 422
        ], 422);
    }

    $data = [
        'name' => $request->name ?? null,
        'Phone' => $request->Phone ?? null,
        'languaje' => $request->languaje ?? null,
    ];

    try {
        $updatedUser = Users::where('id', $userId)->update($data);

        if (!$updatedUser) {
            return response()->json([
                'error' => 'No se encontró el usuario para actualizar',
                'status' => 404
            ], 404);
        }

        return response()->json([
            'message' => 'Usuario actualizado con éxito',
            'data' => [
                'id' => $userId,
                'name' => $request->name ?? null,
                'Phone' => $request->Phone ?? null,
                'languaje' => $request->languaje ?? null,
            ],
            'status' => 200
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Ocurrió un error al actualizar el usuario',
            'details' => $e->getMessage(),
            'status' => 500
        ], 500);
    }
}


public function partialUpdate(Request $request, $userId)
{
    $validatedData = $request->validate([
        'name' => 'sometimes|string|max:255',
        'Phone' => 'sometimes|string|regex:/^(\+?[0-9]{1,3}[-. ]?[0-9]{3}[-. ]?[0-9]{3}[-. ]?[0-9]{4}|[0-9]+)/',
        'languaje' => 'sometimes|string|in:English,Spanish,French',
        'password' => 'sometimes|string|min:8'
    ]);

    $user = Users::findOrFail($userId);

    $user->update($validatedData);

    return response()->json([
        'message' => 'Usuario actualizado parcialmente con éxito',
        'data' => [
            'id' => $userId,
            'name' => $validatedData['name'] ?? $user->name,
            'Phone' => $validatedData['Phone'] ?? $user->Phone,
            'languaje' => $validatedData['languaje'] ?? $user->languaje,
        ],
        'status' => 200
    ], 200);
}


}
