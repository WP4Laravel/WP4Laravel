<?php

namespace WP4Laravel\Gutenberg;


class Parser
{
    public function parse($input)
    {
        $parsedTokens = [];
        $countParsedTokens = 0;
        $matches = null;

        $tokens = explode("\n", $input);

        foreach ($tokens as $token) {
            $matches = null;

            $has_match = preg_match(
                '/<!--\s+(?P<closer>\/)?wp:(?P<namespace>[a-z][a-z0-9_-]*\/)?(?P<name>[a-z][a-z0-9_-]*)\s+(?P<attrs>{(?:(?:[^}]+|}+(?=})|(?!}\s+\/?-->).)*+)?}\s+)?(?P<void>\/)?-->/s',
                $token,
                $matches,
                PREG_OFFSET_CAPTURE,
                0
            );

            // if we get here we probably have catastrophic backtracking or out-of-memory in the PCRE.
            if (false === $has_match) {
                dd('11');

                return array('no-more-tokens', null, null, null, null);
            }

            if ($has_match) {
                list($match, $started_at) = $matches[0];

                $length = strlen($match);
                $is_closer = isset($matches['closer']) && -1 !== $matches['closer'][1];
                $is_void = isset($matches['void']) && -1 !== $matches['void'][1];
                $namespace = $matches['namespace'];
                $namespace = (isset($namespace) && -1 !== $namespace[1]) ? $namespace[0] : 'core/';
                $name = $namespace . $matches['name'][0];
                $has_attrs = isset($matches['attrs']) && -1 !== $matches['attrs'][1];
                $is_opener = !$is_closer && !$is_void;


                if ($is_opener) {
                    $parsedTokens[$countParsedTokens]['type'] = $name;

                    if ($has_attrs) {
                        $parsedTokens[$countParsedTokens]['attributes'] = $matches['attrs'];
                    }
                }

                if ($is_closer) {
                    $countParsedTokens++;
                }
            }

            // we have no more tokens.
            if (0 === $has_match) {
                if (!empty($token)) {
                    if (!isset($parsedTokens[$countParsedTokens]['data'])) {
                            $parsedTokens[$countParsedTokens]['data'] = '';
                    }

                    $parsedTokens[$countParsedTokens]['data'] .= $token;

                }

                continue;

            }



        }

        return $parsedTokens;
    }


}