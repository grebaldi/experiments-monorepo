<?php
namespace PackageFactory\TypedFusion;

final class Parser
{
    public function parse()
    {
        $ast = Ast\Root::create();

        $this->lexer->ignore(...TokenType::IGNORABLES());
        $this->lexer->on(TokenType::KEYWORD_INCLUDE(), function (Token $token) {
            $ast->merge($this->parseInclude($token));
        });
        $this->lexer->on(TokenType::KEYWORD_TYPE(), function (Token $token) {
            $ast->addType($this->parseTypeDeclaration($token));
        });
        $this->lexer->on(TokenType::KEYWORD_PROTOTYPE(), function (Token $token) {
            $ast->addPrototype($this->parsePrototypeDeclaration($token));
        });
    }
}