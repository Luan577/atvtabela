
<?php
class Form
{
  private $message = "";
  private $error = "";
  public function __construct()
  {
    Transaction::open();
  }
  public function controller()
  {
    $form = new Template("view/form.html");
    $form->set("id", "");
    $form->set("nome", "");
    $form->set("quantidade", "");
    $form->set("datahora", "");
    $this->message = $form->saida();
  }
  public function salvar()
  {
    if (isset($_POST['nome']) && isset($_POST['quantidade']) && isset($_POST['datahora'])) {
      try {
        $conexao = Transaction::get();
        $material = new Crud('material');
        $nome = $conexao->quote($_POST['nome']);
        $quantidade = $conexao->quote($_POST['quantidade']);
        $datahora = $conexao->quote($_POST['datahora']);
        if (empty($_POST["id"])) {
          $material->insert("nome,quantidade,datahora", "$nome,$quantidade,$datahora");
        } else {
          $id = $conexao->quote($_POST['id']);
          $material->update("nome=$nome,quantidade=$quantidade,datahora=$datahora", "id=$id");
        }
        $this->message = $material->getMessage();
        $this->error = $material->getError();
      } catch (Exception $e) {
        $this->message = $e->getMessage();
        $this->error = true;
      }
    }
  }
  public function editar()
  {
    if (isset($_GET['id'])) {
      try {
        $conexao = Transaction::get();
        $id = $conexao->quote($_GET['id']);
        $material = new Crud('material');
        $resultado = $material->select("*", "id=$id");
        if (!$material->getError()) {
          $form = new Template("view/form.html");
          foreach ($resultado[0] as $cod => $datahora) {
            $form->set($cod, $datahora);
          }
          $this->message = $form->saida();
        } else {
          $this->message = $material->getMessage();
          $this->error = true;
        }
      } catch (Exception $e) {
        $this->message = $e->getMessage();
        $this->error = true;
      }
    }
  }
  public function getMessage()
  {
    if (is_string($this->error)) {
      return $this->message;
    } else {
      $msg = new Template("view/msg.html");
      if ($this->error) {
        $msg->set("cor", "danger");
      } else {
        $msg->set("cor", "success");
      }
      $msg->set("msg", $this->message);
      $msg->set("uri", "?class=Tabela");
      return $msg->saida();
    }
  }
  public function __destruct()
  {
    Transaction::close();
  }
}