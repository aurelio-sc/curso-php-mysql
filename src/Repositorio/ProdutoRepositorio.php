<?php

class ProdutoRepositorio
{
    private PDO $pdo;
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function opcoesCafe(): array
    {
        $sql = 'SELECT * FROM produtos WHERE tipo = "Café" ORDER BY preco';
        $statement = $this->pdo->query($sql);    
        $produtosCafe = $statement->fetchAll(PDO::FETCH_ASSOC);

        $dadosCafe = array_map(function($cafe) {
            return new Produto(
                        $cafe['id'], 
                        $cafe['tipo'], 
                        $cafe['nome'],  
                        $cafe['descricao'], 
                        $cafe['imagem'], 
                        $cafe['preco']
            );
        }, $produtosCafe);
        
        return $dadosCafe;
    }

    public function opcoesAlmoco(): array
    {
        $sql = 'SELECT * FROM produtos WHERE tipo = "Almoço" ORDER BY preco';
        $statement = $this->pdo->query($sql);    
        $produtosAlmoco = $statement->fetchAll(PDO::FETCH_ASSOC);

        $dadosAlmoco = array_map(function($almoco) {
            return new Produto(
                        $almoco['id'], 
                        $almoco['tipo'], 
                        $almoco['nome'],  
                        $almoco['descricao'], 
                        $almoco['imagem'], 
                        $almoco['preco']
            );
        }, $produtosAlmoco);
        
        return $dadosAlmoco;
    }

    public function buscarTodos(): array
    {
        $sql = 'SELECT * FROM produtos ORDER BY tipo';
        $statement = $this->pdo->query($sql);    
        $produtos = $statement->fetchAll(PDO::FETCH_ASSOC);

        $dados = array_map(function($produto) {
            return new Produto(
                        $produto['id'], 
                        $produto['tipo'], 
                        $produto['nome'],  
                        $produto['descricao'], 
                        $produto['imagem'], 
                        $produto['preco']
            );
        }, $produtos);
        
        return $dados;
    }
    
}