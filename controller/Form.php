
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
        $nome= $conexao->quote($_POST['nome']);
        $quantidade = $conexao->quote($_POST['quantidade']);
        $datahora = $conexao->quote($_POST['datahora']);
        if (empty($_POST["id"])) {
          $material->insert("nome,quantidade,datahora", "$nome,$quantidade,$datahora");
        } else {
          $id = $conexao->quote($_POST['id']);
          $material->update("nome=$nome,quantidade=$quantidade,datahora=$datahora", "id=$id");
        }
      } catch (Exception $e) {
        echo $e->getMessage();
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
        $form = new Template("view/form.html");
        foreach ($resultado[0] as $cod => $valor) {
          $form->set($cod, $valor);
        }
        $this->message = $form->saida();
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