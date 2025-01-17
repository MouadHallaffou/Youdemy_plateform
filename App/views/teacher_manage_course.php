<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Youdemy - Gestion des Cours</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h2 class="text-2xl font-bold mb-4">All Courses</h2>
        <table id="example" class="table-auto w-full">
            <thead>
                <tr>
                    <th class="px-4 py-2">id</th>
                    <th class="px-4 py-2">titre</th>
                    <th class="px-4 py-2">contenu</th>
                    <th class="px-4 py-2">status</th>
                    <th class="px-4 py-2">Agecategory</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="border px-4 py-2">1</td>
                    <td class="border px-4 py-2">boite</td>
                    <td class="border px-4 py-2">video</td>
                    <td class="border px-4 py-2">soumis</td>
                    <td class="border px-4 py-2">front</td>
                    <td class="border px-4 py-2">
                        <button type="button"
                            class="mr-3 text-sm bg-blue-500 hover:bg-blue-700 text-white py-1 px-2 rounded focus:outline-none focus:shadow-outline">Edit</button>
                        <button type="button"
                            class="text-sm bg-red-500 hover:bg-red-700 text-white py-1 px-2 rounded focus:outline-none focus:shadow-outline">Delete</button>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable({

            });
        });
    </script>
</body>

</html>