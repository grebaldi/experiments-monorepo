<?php
namespace PackageFactory\TypedFusion;

final class Parser
{
    public function parse()
    {
        $this->lexer->cosume(TokenType::WHITESPACE());
        
        if ($token = $this->lexer->next(TokenType::KEYWORD_INCLUDE())) {
            $this->parseInclude($token);
        } else if($token = $this->lexer->next(TokenType::KEYWORD_TYPE()))
    }
}