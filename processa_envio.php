<?php
    require "./Bibliotecas/PHPMailer/Exception.php";
    /* require "./Bibliotecas/PHPMailer/OAuth.php";
    require "./Bibliotecas/PHPMailer/OAuthTokenProvider.php"; */
    require "./Bibliotecas/PHPMailer/PHPMailer.php";
    require "./Bibliotecas/PHPMailer/POP3.php";
    require "./Bibliotecas/PHPMailer/SMTP.php";

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\SMTP;
    /* use PHPMailer\PHPMailer\OAuth;
    use PHPMailer\PHPMailer\OAuthTokenProvider; */

    class Mensagem {
        private $para = null;
        private $assunto = null;
        private $mensagem = null;

        public function __get($atributo){
            return $this->$atributo;
        }

        public function __set($atributo, $valor){
            $this->$atributo = $valor;
        }

        public function mensagemValida(){
            if (empty($this->para) || empty($this->assunto) || empty($this->mensagem)) {
                return false;
            }

            return true;
        }
    }

    $mensagem = new Mensagem();
    $mensagem->__set('para', $_POST['para']);
    $mensagem->__set('assunto', $_POST['assunto']);
    $mensagem->__set('mensagem', $_POST['mensagem']);

    /* echo '<pre>';
    print_r($mensagem);
    echo '</pre>'; */

    if (!$mensagem->mensagemValida()) {
        echo 'Mensagem inválida';
        die();
    }

    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'teste@gmail.com.br';                     //SMTP username
        $mail->Password   = 'segredo';                            //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
        $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('teste@gmail.com', 'Alguém da Silva');
        $mail->addAddress($mensagem->__get('para'));     //Add a recipient
        #$mail->addAddress('ellen@example.com');               //Name is optional
        #$mail->addReplyTo('info@example.com', 'Information');
        #$mail->addCC('cc@example.com');
        #$mail->addBCC('bcc@example.com');

        //Attachments
        #$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        #$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $mensagem->__get('assunto');
        $mail->Body    = $mensagem->__get('mensagem');
        $mail->AltBody = 'É necessário utilizar um client que suporte HTML para ter acesso total ao conteúdo desta mensagem.';

        $mail->send();
        echo 'E-mail enviado com sucesso.';
    } catch (Exception $e) {
        echo "Não foi possível enviar este e-mail. Por favor tente novamente mais tarde. Detalhes do erro: {$mail->ErrorInfo}";
    }

?>