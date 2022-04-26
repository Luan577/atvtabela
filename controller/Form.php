
  
<?php
class Form
{
  private $message = "";
  public function __construct()
  {
    Transaction::open();
  }
  public function controller()
  {
    $form = new Template("view/form.html");
    $this->message = $form->saida();
  }
  public function salvar()
  {
    if (isset($_POST["nome"]) && isset($_POST["quantidade"]) && isset($_POST["datahora"])) {
      try {
        $conexao = Transaction::get();
        $computador = new Crud("material");
        $marca = $conexao->quote($_POST["nome"]);
        $config = $conexao->quote($_POST["quantidade"]);
        $valor = $conexao->quote($_POST["datahora"]);
        $resultado = $computador->insert("nome, quantidade, datahora", "$marca, $config, $valor");
      } catch (Exception $e) {
        echo $e->getMessage();
      }
    }
  }
  public function getMessage()
  {
    return $this->message;
  }
  public function __destruct()
  {
    Transaction::close();
  }
}