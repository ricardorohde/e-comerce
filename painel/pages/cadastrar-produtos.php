<div class="box-content">
    <h2><i class="fa fa-pencil"></i> Cadastrar Produto</h2>
    
    <form method="post" enctype="multipart/form-data">
    <?php
        if (isset($_POST['acao'])) {
            $nome = $_POST['nome'];
            $descicao = $_POST['descricao'];
            $largura = $_POST['largura'];
            $altura = $_POST['altura'];
            $peso = $_POST['peso'];
            $comprimento = $_POST['comprimento'];
            $quantidade = $_POST['quantidade'];
            $preco = Painel::formatarMoedaBd($_POST['preco']);
            $imagens = array();
            $amountFiles = count($_FILES['imagem']['name']);

            $sucesso = true;

            if ($_FILES['imagem']['name'][0] != '') {             

                for ($i=0; $i < $amountFiles; $i++) { 
                    $imagemAtual = ['type'=>$_FILES['imagem']['type'][$i],
                    'size'=>$_FILES['imagem']['size'][$i]];
                    if (Painel::imagemValida($imagemAtual) == false) {
                        $sucesso = false;
                        Painel::alert('erro', 'Uma das imagens selecionadas é inválida! ');
                        break;
                    }
                }

                if ($sucesso) {
                    //Cadastrar informações, imagens e realizar upload
                    for ($i=0; $i < $amountFiles; $i++) { 
                        $imagemAtual = ['tmp_name'=>$_FILES['imagem']['tmp_name'][$i],
                            'name'=>$_FILES['imagem']['name'][$i]];
                        $imagens[] = Painel::uploadFile($imagemAtual);
                    }

                    $sql = MySql::conectar()->prepare("INSERT INTO `tb_admin.estoque` VALUES (null,?,?,?,?,?,?,?,?)");
                    $sql->execute(array($nome,$descicao,$largura,$altura,$comprimento,$peso,$quantidade,$preco));
                    $lastId = MySql::conectar()->lastInsertId();
                    foreach ($imagens as $key => $value) {
                        MySql::conectar()->exec("INSERT INTO `tb_admin.estoque_imagens` VALUES (null,$lastId,'$value')");
                    }
                    Painel::alert('sucesso', 'O produto foi cadastrado com sucesso!');
                }

        }else {
            $sucesso = false;
            Painel::alert('erro','Você precisa selecionar pelo menos uma imagem');
        }
            
        }
    ?>
        <div class="form-group">
            <label>Nome do Produto:</label>
			<input type="text" name="nome">
		</div><!--form-group-->

        <div class="form-group">
            <label>Descrição do Produto:</label>
			<textarea name="descricao" ></textarea>
		</div><!--form-group-->

        <div class="form-group">
            <label>Largura do Produto:</label>
			<input type="number" name="largura" min="0" max="900" value="0">
        </div><!--form-group-->
        
        <div class="form-group">
            <label>Altura do Produto:</label>
			<input type="number" name="altura" min="0" max="900" value="0">
        </div><!--form-group-->
        
        <div class="form-group">
            <label>Comprimento do Produto:</label>
			<input type="number" name="comprimento" min="0" max="900" value="0">
        </div><!--form-group-->

        <div class="form-group">
            <label>Peso do Produto:</label>
			<input type="number" name="peso" min="0" max="50000" value="0">
        </div><!--form-group-->

        <div class="form-group">
            <label>Quantidade Atual do Produto:</label>
			<input type="number" name="quantidade" min="0" max="900" value="0">
        </div><!--form-group-->

        <div class="form-group">
            <label>Preço:</label>
			<input type="text" name="preco">
        </div><!--form-group-->
        
        <div class="form-group">
            <label>Selecione a Imagens:</label>
			<input multiple type="file" name="imagem[]">
        </div><!--form-group-->
        <input type="submit" name="acao" value="Cadastrar Produto!">
    </form>
</div>