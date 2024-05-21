<?php

class ProdutoRepositorio
{
    private PDO $pdo;
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function formarObjeto($dados): Produto
    {
        return new Produto(
            $dados['id'],
            $dados['tipo'],
            $dados['nome'],
            $dados['descricao'],
            $dados['preco'],
            $dados['imagem']
        );
    }

    public function opcoesCafe(): array
    {
        $sql = 'SELECT * FROM produtos WHERE tipo = "Café" ORDER BY preco';
        $statement = $this->pdo->query($sql);    
        $produtosCafe = $statement->fetchAll(PDO::FETCH_ASSOC);

        $dadosCafe = array_map(function ($cafe){
            return $this->formarObjeto($cafe);
        },$produtosCafe);
        
        return $dadosCafe;
    }

    public function opcoesAlmoco(): array
    {
        $sql = 'SELECT * FROM produtos WHERE tipo = "Almoço" ORDER BY preco';
        $statement = $this->pdo->query($sql);    
        $produtosAlmoco = $statement->fetchAll(PDO::FETCH_ASSOC);

        $dadosAlmoco = array_map(function ($almoco){
            return $this->formarObjeto($almoco);
        },$produtosAlmoco);
        
        return $dadosAlmoco;
    }

    public function buscarTodos(): array
    {
        $sql = 'SELECT * FROM produtos ORDER BY tipo';
        $statement = $this->pdo->query($sql);    
        $produtos = $statement->fetchAll(PDO::FETCH_ASSOC);

        $dados = array_map(function ($produto){
            return $this->formarObjeto($produto);
        },$produtos);
        
        return $dados;
    }

    public function deletar(string $id): void
    {
      $sql = "DELETE FROM  produtos WHERE id = :id";
      $statement = $this->pdo->prepare($sql);
      $statement->bindValue('id', $id, PDO::PARAM_INT);
      $statement->execute();
    }

    public function salvar(Produto $produto)
    {
        $sql = "INSERT INTO produtos (tipo, nome, descricao, preco, imagem) VALUES (:tipo, :nome, :descricao, :preco, :imagem)";
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue('tipo', $produto->getTipo());
        $statement->bindValue('nome', $produto->getNome());
        $statement->bindValue('descricao', $produto->getDescricao());
        $statement->bindValue('preco', $produto->getPreco());
        $statement->bindValue('imagem', $produto->getImagem());
        $statement->execute();
    }

    public function buscar(string $id): ?Produto
    {
        $sql = 'SELECT * FROM produtos WHERE id = :id';
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue('id', $id, PDO::PARAM_INT);
        $statement->execute();
        $dados = $statement->fetch(PDO::FETCH_ASSOC);
        if ($dados === false) {
            return null;
        }
        return $this->formarObjeto($dados);
    }

    public function atualizar(Produto $produto): void
    {
        $sql = "UPDATE produtos SET tipo = :tipo, nome = :nome, descricao = :descricao, preco = :preco WHERE id = :id";
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue('id', $produto->getId());
        $statement->bindValue('tipo', $produto->getTipo());
        $statement->bindValue('nome', $produto->getNome());
        $statement->bindValue('descricao', $produto->getDescricao());
        $statement->bindValue('preco', $produto->getPreco());        
        $statement->execute();
        if($produto->getImagem() !== 'logo-serenatto.png'){            
            $this->atualizarFoto($produto);
        }
    }

    private function atualizarFoto(Produto $produto)
    {
        $sql = "UPDATE produtos SET imagem = :imagem WHERE id = :id";
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue('imagem', $produto->getImagem());
        $statement->bindValue('id', $produto->getId());
        $statement->execute();
    }
}