<?php

namespace App\Controllers;

//os recursos do miniframework

use APP\Models\Tweet;
use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action {

    public function timeline(){

        $this->validaAutenticacao();

        // recuperar os tweets do banco
        $tweet = Container::getModel('tweet');

        $tweet->__set('id_usuario', $_SESSION['id']);

        $tweets = $tweet->getAll(); 

        $this->view->tweets = $tweet->getAll(); 

        $this->render('timeline');
        
    }

    public function tweet(){

        $this->validaAutenticacao();

        $tweet = Container::getModel('Tweet');

        $tweet->__set('id_usuario', $_SESSION['id']);
        $tweet->__set('tweet', $_POST['tweet']);

        $tweet->salvar();

        header('location: /timeline');
        
    }

    public function validaAutenticacao(){
        session_start();

        if(!isset($_SESSION['id']) || empty($_SESSION['id']) || !isset($_SESSION['nome']) || empty($_SESSION['nome'])){
            header('location: /?login=erro');
        }
    }

    public function quemSeguir(){

        $this->validaAutenticacao();

        $pesquisarPor = isset($_GET['pesquisarPor']) ? $_GET['pesquisarPor'] : '';
        $usuarios = array();

        if($pesquisarPor != ''){
            $usuario = Container::getModel('usuario');
            $usuario->__set('id',$_SESSION['id']);
            $usuario->__set('nome',$pesquisarPor);
            $usuarios = $usuario->getAll();

        }

        $this->view->usuarios = $usuarios;

        $this->render('quemSeguir');
        
    }

    public function acao(){

        $this->validaAutenticacao();

        $acao = isset($_GET['acao']) ? $_GET['acao'] : '';
        $id_usuario_seguindo = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : '';

        $usuario = Container::getModel('usuario');
        $usuario->__set('id',$_SESSION['id']);

        if($acao == 'seguir'){
            $usuario->seguirUsuario($id_usuario_seguindo);

        }else if($acao == 'deixar_de_seguir'){
            $usuario->deixarSeguirUsuario($id_usuario_seguindo);
        }

        header('location: /quem_seguir');

    }

}

?>