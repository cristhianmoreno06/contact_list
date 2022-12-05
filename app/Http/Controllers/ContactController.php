<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $contacts = Contact::all();

            if ($contacts) {
                return response()->json([
                    'code' => Response::HTTP_OK,
                    'title' => 'Listado de contactos',
                    'message' => 'Se listaron los contactos correctamente',
                    "source" => $contacts,
                ], Response::HTTP_OK);
            }

            return response()->json([
                'code' => Response::HTTP_BAD_REQUEST,
                'title' => 'Listado de contactos',
                'message' => 'No se listaron los contactos correctamente',
                "source" => $contacts,
            ], Response::HTTP_OK);

        }catch (Throwable $throwable){
            return response()->json([
                "title" => 'Error interno del sistema',
                "error" => $throwable->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display a listing of the resource for id.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function indexForId(int $id)
    {
        try {
            $contacts = Contact::whereId($id)->first();

            if (!is_null($contacts)) {
                return response()->json([
                    'code' => Response::HTTP_OK,
                    'title' => 'Listado de contactos',
                    'message' => 'Se listaron los contactos correctamente',
                    "source" => $contacts,
                ], Response::HTTP_OK);
            }

            return response()->json([
                'code' => Response::HTTP_BAD_REQUEST,
                'title' => 'Listado de contactos',
                'message' => 'No se listaron los contactos correctamente',
                "source" => $contacts,
            ], Response::HTTP_OK);

        }catch (Throwable $throwable){
            return response()->json([
                "title" => 'Error interno del sistema',
                "error" => $throwable->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function storeOrUpdateContact(Request $request): JsonResponse
    {
        try {
            $form = json_decode($request->get('form'));
            $getContact = $form->contact;

            if (is_null($getContact->id)) {
                $contact = new Contact();
            } else {
                $contact = Contact::whereId($getContact->id)->first();
            }

            $contact->name = $getContact->name;
            $contact->phone = $getContact->phone;
            $contact->Birthdate = $getContact->Birthdate;
            $contact->address = $getContact->address;
            $contact->email = $getContact->email;
            $contact->save();

            if (!$contact->save()) {
                return response()->json([
                    'code' => Response::HTTP_BAD_REQUEST,
                    'title' => 'Creación de contacto',
                    'message' => 'Error al crear o actualizar el contacto en el sistema',
                    "source" => $contact,
                ], Response::HTTP_OK);
            }

            return response()->json([
                'code' => Response::HTTP_OK,
                'title' => 'Creación de contacto',
                'message' => 'Creación o Actualización del contacto correctamente en el sistema',
                "source" => $contact,
            ], Response::HTTP_OK);

        }catch (Throwable $throwable) {
            return response()->json([
                "title" => 'Error interno del sistema',
                "error" => $throwable->getMessage(). ' linea = ' . $throwable->getLine()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {

            $contact = Contact::whereId($id)->first();
            $contact->name = $request->get('name');
            $contact->phone = $request->get('phone');
            $contact->Birthdate = $request->get('Birthdate');
            $contact->address = $request->get('address');
            $contact->email = $request->get('email');

            if ($contact->save()) {
                return response()->json([
                    'code' => Response::HTTP_OK,
                    'title' => 'Actualización de contacto',
                    'message' => 'El contacto se actualizo correctamente en el sistema',
                    "source" => $contact,
                ], Response::HTTP_OK);
            }

            return response()->json([
                'code' => Response::HTTP_BAD_REQUEST,
                'title' => 'Actualización de contacto',
                'message' => 'Hubo un error al actualizar el contacto en el sistema',
                "source" => $contact,
            ], Response::HTTP_OK);

        }catch (Throwable $throwable) {
            return response()->json([
                "title" => 'Error interno del sistema',
                "error" => $throwable->getMessage(). ' linea = ' . $throwable->getLine()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id)
    {
        try {
            $contact = Contact::whereId($id)->first();

            if ($contact->delete()) {
                return response()->json([
                    'code' => Response::HTTP_OK,
                    'title' => 'Eliminación de contacto',
                    'message' => 'El contacto se elimino correctamente en el sistema',
                    "source" => $contact,
                ], Response::HTTP_OK);
            }

            return response()->json([
                'code' => Response::HTTP_BAD_REQUEST,
                'title' => 'Eliminación de contacto',
                'message' => 'Hubo un error al elimino el contacto en el sistema',
                "source" => $contact,
            ], Response::HTTP_OK);

        }catch (Throwable $throwable) {
            return response()->json([
                "title" => 'Error interno del sistema',
                "error" => $throwable->getMessage(). ' linea = ' . $throwable->getLine()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
