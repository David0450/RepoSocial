<?php


class ChatController {
    protected $chatModel;

    public function __construct() {
        $this->chatModel = new Chat();
    }

    public function procesarMensaje($emisorId, $receptorId, $mensaje) {
        $this->chatModel->guardarMensajePrivado($emisorId, $receptorId, $mensaje);
        return [
            'from' => $emisorId,
            'to' => $receptorId,
            'text' => $mensaje
        ];
    }
}