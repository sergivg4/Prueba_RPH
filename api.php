<?php
$dataset = file_get_contents('dataset.json');
$books = json_decode($dataset, true);

function searchBooks($books, $text) {
    $savedBooks = array();
    $savedAutors = array();

    //guarda todos los autores y libros que coincidan con el $text en sus respectivos arrays
    foreach ($books as $book) {
        if (stripos($book['titulo'], $text) !== false || stripos($book['autor'], $text) !== false) {
            $savedBooks[] = $book;
            $savedAutors[$book['autor']]['libros'][] = $book;
        }
    }

    // ordena los DOS últimos libros del autor DESC
    foreach ($savedAutors as &$autor) {
        usort($autor['libros'], function($a, $b) {
            return strcmp($b['fecha_nov'], $a['fecha_nov']);
        });

        $autor['libros_recientes'] = array_slice($autor['libros'], 0, 2);
        unset($autor['libros']);
    }

    return array(
        'libros' => $savedBooks,
        'autores' => $savedAutors
    );
}

// almacena el GET ?texto
$text = isset($_GET['texto']) ? $_GET['texto'] : '';

$apiResponse = searchBooks($books, $text);

header('Content-Type: application/json');
echo json_encode($apiResponse);
?>
