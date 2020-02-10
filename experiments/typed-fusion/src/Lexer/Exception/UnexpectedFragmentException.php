<?php
namespace PackageFactory\TypedFusion\Lexer\Exception;

use PackageFactory\TypedFusion\Lexer as Lexer;
use PackageFactory\TypedFusion\Source\Fragment;

final class UnexpectedFragmentException extends Lexer\Exception
{
    /**
     * @var Fragment
     */
    private $fragment;

    /**
     * @var string
     */
    private $reason;

    private function __construct(Fragment $fragment, string $reason)
    {
        $this->fragment = $fragment;
        $this->reason = $reason;

        parent::__construct($this->reason);
    }

    public static function whileDisambiguatingLineAndBlockComment(
        Fragment $fragment
    ): UnexpectedFragmentException {
        return new UnexpectedFragmentException(
            $fragment,
            'While trying to distinguish between the start of a line comment ("//") and ' .
            'the start of a block comment ("/*"), a "' . $fragment->getValue() . '" was ' .
            'encountered.'
        );
    }

    public static function whileTokenizingArrowOperatorInExpression(
        Fragment $fragment
    ): UnexpectedFragmentException {
        return new UnexpectedFragmentException(
            $fragment,
            'While trying to tokenize the arrow operator ("=>") in an expression, first ' .
            'a "=" and then an unexpected "' . $fragment->getValue() . '" was ' .
            'encountered.'
        );
    }

    public static function whileTokenizingIncludeStatement(
        Fragment $fragment
    ): UnexpectedFragmentException {
        return new UnexpectedFragmentException(
            $fragment,
            'While trying to tokenize and include statement ("include: ...") an unexpected ' .
            '"' . $fragment->getValue() . '" was encountered, instead of the expected ":".'
        );
    }

    public static function whileStartingRestOrPathSeparatorDisambiguation(
        Fragment $fragment
    ): UnexpectedFragmentException {
        return new UnexpectedFragmentException(
            $fragment,
            'The tokenizer was asked to distinuguish between a path separator (".") and ' .
            'a spread operator ("..."), but encountered an unexpected "' . $fragment->getValue() . '" ' .
            'right away.'
        );
    }

    public static function whileCapturingRestOperator(
        Fragment $fragment
    ): UnexpectedFragmentException {
        return new UnexpectedFragmentException(
            $fragment,
            'While trying to tokenize a rest operator ("..."), a sequence of periods was encountered, that ' .
            'was too long: "' . $fragment->getValue() . '". The rest operator is supposed to contain ' .
            'exactly 3 periods.'
        );
    }

    public static function whileCapturingPathSeparator(
        Fragment $fragment
    ): UnexpectedFragmentException {
        return new UnexpectedFragmentException(
            $fragment,
            'While trying to tokenize a path separator ("."), an unexpected ' .
            '"' . $fragment->getValue() . '" was encountered.'
        );
    }

    public function getFragment(): Fragment
    {
        return $this->fragment;
    }

    public function getReason(): string
    {
        return $this->reason;
    }
}
