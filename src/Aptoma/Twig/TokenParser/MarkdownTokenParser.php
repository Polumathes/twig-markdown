<?php

namespace Aptoma\Twig\TokenParser;

use Aptoma\Twig\Node\MarkdownNode;
use Aptoma\Twig\Extension\MarkdownParserInterface;

/**
 * @author Gunnar Lium <gunnar@aptoma.com>
 * @author Joris Berthelot <joris@berthelot.tel>
 */
class MarkdownTokenParser extends \Twig_TokenParser
{
    /**
     * @var The Markdown parser engine
     */
    protected $markdownParser;

    /**
     * @param MarkdownParserInterface $markdownParser The Markdown parser engine
     */
    public function __construct(MarkdownParserInterface $markdownParser)
    {
        $this->markdownParser = $markdownParser;
    }

    /**
     * Markdown parser engine getter
     *
     * @return MarkdownParserInterface
     */
    public function getParser()
    {
        return $this->markdownParser;
    }

    /**
     * Parses a token and returns a node.
     *
     * @param \Twig_Token $token A \Twig_Token instance
     *
     * @throws \Twig_Error_Syntax
     * @return \Twig_NodeInterface A \Twig_NodeInterface instance
     */
    public function parse(\Twig_Token $token)
    {
        $lineno = $token->getLine();
        
        $this->parser->getStream()->expect(\Twig_Token::BLOCK_END_TYPE);
        $body = $this->parser->subparse(array($this, 'decideMarkdownEnd'), true);
        $this->parser->getStream()->expect(\Twig_Token::BLOCK_END_TYPE);

        return new MarkdownNode($body, $lineno, $this->getTag());
    }

    /**
     * Decide if current token marks end of Markdown block.
     *
     * @param \Twig_Token $token
     * @return bool
     */
    public function decideMarkdownEnd(\Twig_Token $token)
    {
        return $token->test('endmarkdown');
    }

    /**
     * Gets the tag name associated with this token parser.
     *
     * @return string The tag name
     */
    public function getTag()
    {
        return 'markdown';
    }
}
