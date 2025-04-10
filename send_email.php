<?php
header('Content-Type: application/json');

// Función para guardar el mensaje en el archivo JSON
function saveMessage($data) {
    $file = 'messages.json';
    $messages = [];
    
    // Si el archivo existe, leer los mensajes existentes
    if (file_exists($file)) {
        $messages = json_decode(file_get_contents($file), true) ?? [];
    }
    
    // Agregar el nuevo mensaje con timestamp
    $messages[] = [
        'name' => $data['fullname'],
        'email' => $data['email'],
        'message' => $data['message'],
        'date' => date('Y-m-d H:i:s')
    ];
    
    // Guardar los mensajes en el archivo
    file_put_contents($file, json_encode($messages, JSON_PRETTY_PRINT));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['fullname'] ?? '';
    $email = $_POST['email'] ?? '';
    $message = $_POST['message'] ?? '';

    if (empty($name) || empty($email) || empty($message)) {
        echo json_encode(['success' => false, 'message' => 'Por favor complete todos los campos']);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Por favor ingrese un correo electrónico válido']);
        exit;
    }

    try {
        // Guardar el mensaje
        saveMessage([
            'fullname' => $name,
            'email' => $email,
            'message' => $message
        ]);

        echo json_encode(['success' => true, 'message' => '¡Mensaje enviado con éxito!']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Hubo un error al guardar el mensaje']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
} 