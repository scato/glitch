<?php

namespace Glitch\Grammar;

use Glitch\Grammar\Tree\ActionNode;
use Glitch\Grammar\Tree\BinaryNode;
use Glitch\Grammar\Tree\CallNode;
use Glitch\Grammar\Tree\FunctionNode;
use Glitch\Grammar\Tree\ReferenceNode;
use Glitch\Grammar\Tree\StringNode;
use Glitch\Grammar\Tree\TernaryNode;

class GlitchExpression
{
    protected $string;
    protected $position;
    protected $value;
    protected $cache;
    protected $cut;
    protected $errors;
    protected $warnings;

    protected function parseExpression()
    {
        $_position = $this->position;

        if (isset($this->cache['Expression'][$_position])) {
            $_success = $this->cache['Expression'][$_position]['success'];
            $this->position = $this->cache['Expression'][$_position]['position'];
            $this->value = $this->cache['Expression'][$_position]['value'];

            return $_success;
        }

        $_position1 = $this->position;
        $_cut2 = $this->cut;

        $this->cut = false;
        $_success = $this->parseActionLiteral();

        if (!$_success && !$this->cut) {
            $this->position = $_position1;

            $_success = $this->parseFunctionLiteral();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position1;

            $_success = $this->parseTernaryExpression();
        }

        $this->cut = $_cut2;

        $this->cache['Expression'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'Expression');
        }

        return $_success;
    }

    protected function parseTernaryExpression()
    {
        $_position = $this->position;

        if (isset($this->cache['TernaryExpression'][$_position])) {
            $_success = $this->cache['TernaryExpression'][$_position]['success'];
            $this->position = $this->cache['TernaryExpression'][$_position]['position'];
            $this->value = $this->cache['TernaryExpression'][$_position]['value'];

            return $_success;
        }

        $_position4 = $this->position;
        $_cut5 = $this->cut;

        $this->cut = false;
        $_value3 = array();

        $_success = $this->parseEqualityExpression();

        if ($_success) {
            $first = $this->value;
        }

        if ($_success) {
            $_value3[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value3[] = $this->value;

            if (substr($this->string, $this->position, strlen("?")) === "?") {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen("?"));
                $this->position += strlen("?");
            } else {
                $_success = false;

                $this->report($this->position, '"?"');
            }
        }

        if ($_success) {
            $_value3[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value3[] = $this->value;

            $_success = $this->parseExpression();

            if ($_success) {
                $second = $this->value;
            }
        }

        if ($_success) {
            $_value3[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value3[] = $this->value;

            if (substr($this->string, $this->position, strlen(":")) === ":") {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen(":"));
                $this->position += strlen(":");
            } else {
                $_success = false;

                $this->report($this->position, '":"');
            }
        }

        if ($_success) {
            $_value3[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value3[] = $this->value;

            $_success = $this->parseExpression();

            if ($_success) {
                $third = $this->value;
            }
        }

        if ($_success) {
            $_value3[] = $this->value;

            $this->value = $_value3;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$first, &$second, &$third) {
                return new TernaryNode($first, $second, $third);
            });
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position4;

            $_success = $this->parseEqualityExpression();
        }

        $this->cut = $_cut5;

        $this->cache['TernaryExpression'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'TernaryExpression');
        }

        return $_success;
    }

    protected function parseEQUALITY_OPERATOR()
    {
        $_position = $this->position;

        if (isset($this->cache['EQUALITY_OPERATOR'][$_position])) {
            $_success = $this->cache['EQUALITY_OPERATOR'][$_position]['success'];
            $this->position = $this->cache['EQUALITY_OPERATOR'][$_position]['position'];
            $this->value = $this->cache['EQUALITY_OPERATOR'][$_position]['value'];

            return $_success;
        }

        $_position6 = $this->position;
        $_cut7 = $this->cut;

        $this->cut = false;
        if (substr($this->string, $this->position, strlen("===")) === "===") {
            $_success = true;
            $this->value = substr($this->string, $this->position, strlen("==="));
            $this->position += strlen("===");
        } else {
            $_success = false;

            $this->report($this->position, '"==="');
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position6;

            if (substr($this->string, $this->position, strlen("!==")) === "!==") {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen("!=="));
                $this->position += strlen("!==");
            } else {
                $_success = false;

                $this->report($this->position, '"!=="');
            }
        }

        $this->cut = $_cut7;

        $this->cache['EQUALITY_OPERATOR'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'EQUALITY_OPERATOR');
        }

        return $_success;
    }

    protected function parseEqualityExpression()
    {
        $_position = $this->position;

        if (isset($this->cache['EqualityExpression'][$_position])) {
            $_success = $this->cache['EqualityExpression'][$_position]['success'];
            $this->position = $this->cache['EqualityExpression'][$_position]['position'];
            $this->value = $this->cache['EqualityExpression'][$_position]['value'];

            return $_success;
        }

        $_value12 = array();

        $_success = $this->parseRelationalExpression();

        if ($_success) {
            $left = $this->value;
        }

        if ($_success) {
            $_value12[] = $this->value;

            $_value10 = array();
            $_cut11 = $this->cut;

            while (true) {
                $_position9 = $this->position;

                $this->cut = false;
                $_value8 = array();

                $_success = $this->parse_();

                if ($_success) {
                    $_value8[] = $this->value;

                    $_success = $this->parseEQUALITY_OPERATOR();

                    if ($_success) {
                        $operator = $this->value;
                    }
                }

                if ($_success) {
                    $_value8[] = $this->value;

                    $_success = $this->parse_();
                }

                if ($_success) {
                    $_value8[] = $this->value;

                    $_success = $this->parseRelationalExpression();

                    if ($_success) {
                        $right = $this->value;
                    }
                }

                if ($_success) {
                    $_value8[] = $this->value;

                    $this->value = $_value8;
                }

                if ($_success) {
                    $this->value = call_user_func(function () use (&$left, &$operator, &$right) {
                        $left = new BinaryNode($operator, $left, $right);
                    });
                }

                if (!$_success) {
                    break;
                }

                $_value10[] = $this->value;
            }

            if (!$this->cut) {
                $_success = true;
                $this->position = $_position9;
                $this->value = $_value10;
            }

            $this->cut = $_cut11;
        }

        if ($_success) {
            $_value12[] = $this->value;

            $this->value = $_value12;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$left, &$operator, &$right) {
                return $left;
            });
        }

        $this->cache['EqualityExpression'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'EqualityExpression');
        }

        return $_success;
    }

    protected function parseRELATIONAL_OPERATOR()
    {
        $_position = $this->position;

        if (isset($this->cache['RELATIONAL_OPERATOR'][$_position])) {
            $_success = $this->cache['RELATIONAL_OPERATOR'][$_position]['success'];
            $this->position = $this->cache['RELATIONAL_OPERATOR'][$_position]['position'];
            $this->value = $this->cache['RELATIONAL_OPERATOR'][$_position]['value'];

            return $_success;
        }

        $_position13 = $this->position;
        $_cut14 = $this->cut;

        $this->cut = false;
        if (substr($this->string, $this->position, strlen("<=")) === "<=") {
            $_success = true;
            $this->value = substr($this->string, $this->position, strlen("<="));
            $this->position += strlen("<=");
        } else {
            $_success = false;

            $this->report($this->position, '"<="');
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position13;

            if (substr($this->string, $this->position, strlen(">=")) === ">=") {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen(">="));
                $this->position += strlen(">=");
            } else {
                $_success = false;

                $this->report($this->position, '">="');
            }
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position13;

            if (substr($this->string, $this->position, strlen("<")) === "<") {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen("<"));
                $this->position += strlen("<");
            } else {
                $_success = false;

                $this->report($this->position, '"<"');
            }
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position13;

            if (substr($this->string, $this->position, strlen(">")) === ">") {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen(">"));
                $this->position += strlen(">");
            } else {
                $_success = false;

                $this->report($this->position, '">"');
            }
        }

        $this->cut = $_cut14;

        $this->cache['RELATIONAL_OPERATOR'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'RELATIONAL_OPERATOR');
        }

        return $_success;
    }

    protected function parseRelationalExpression()
    {
        $_position = $this->position;

        if (isset($this->cache['RelationalExpression'][$_position])) {
            $_success = $this->cache['RelationalExpression'][$_position]['success'];
            $this->position = $this->cache['RelationalExpression'][$_position]['position'];
            $this->value = $this->cache['RelationalExpression'][$_position]['value'];

            return $_success;
        }

        $_value19 = array();

        $_success = $this->parseAdditiveExpression();

        if ($_success) {
            $left = $this->value;
        }

        if ($_success) {
            $_value19[] = $this->value;

            $_value17 = array();
            $_cut18 = $this->cut;

            while (true) {
                $_position16 = $this->position;

                $this->cut = false;
                $_value15 = array();

                $_success = $this->parse_();

                if ($_success) {
                    $_value15[] = $this->value;

                    $_success = $this->parseRELATIONAL_OPERATOR();

                    if ($_success) {
                        $operator = $this->value;
                    }
                }

                if ($_success) {
                    $_value15[] = $this->value;

                    $_success = $this->parse_();
                }

                if ($_success) {
                    $_value15[] = $this->value;

                    $_success = $this->parseAdditiveExpression();

                    if ($_success) {
                        $right = $this->value;
                    }
                }

                if ($_success) {
                    $_value15[] = $this->value;

                    $this->value = $_value15;
                }

                if ($_success) {
                    $this->value = call_user_func(function () use (&$left, &$operator, &$right) {
                        $left = new BinaryNode($operator, $left, $right);
                    });
                }

                if (!$_success) {
                    break;
                }

                $_value17[] = $this->value;
            }

            if (!$this->cut) {
                $_success = true;
                $this->position = $_position16;
                $this->value = $_value17;
            }

            $this->cut = $_cut18;
        }

        if ($_success) {
            $_value19[] = $this->value;

            $this->value = $_value19;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$left, &$operator, &$right) {
                return $left;
            });
        }

        $this->cache['RelationalExpression'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'RelationalExpression');
        }

        return $_success;
    }

    protected function parseADDITIVE_OPERATOR()
    {
        $_position = $this->position;

        if (isset($this->cache['ADDITIVE_OPERATOR'][$_position])) {
            $_success = $this->cache['ADDITIVE_OPERATOR'][$_position]['success'];
            $this->position = $this->cache['ADDITIVE_OPERATOR'][$_position]['position'];
            $this->value = $this->cache['ADDITIVE_OPERATOR'][$_position]['value'];

            return $_success;
        }

        $_position20 = $this->position;
        $_cut21 = $this->cut;

        $this->cut = false;
        if (substr($this->string, $this->position, strlen("+")) === "+") {
            $_success = true;
            $this->value = substr($this->string, $this->position, strlen("+"));
            $this->position += strlen("+");
        } else {
            $_success = false;

            $this->report($this->position, '"+"');
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position20;

            if (substr($this->string, $this->position, strlen("-")) === "-") {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen("-"));
                $this->position += strlen("-");
            } else {
                $_success = false;

                $this->report($this->position, '"-"');
            }
        }

        $this->cut = $_cut21;

        $this->cache['ADDITIVE_OPERATOR'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'ADDITIVE_OPERATOR');
        }

        return $_success;
    }

    protected function parseAdditiveExpression()
    {
        $_position = $this->position;

        if (isset($this->cache['AdditiveExpression'][$_position])) {
            $_success = $this->cache['AdditiveExpression'][$_position]['success'];
            $this->position = $this->cache['AdditiveExpression'][$_position]['position'];
            $this->value = $this->cache['AdditiveExpression'][$_position]['value'];

            return $_success;
        }

        $_value26 = array();

        $_success = $this->parseMultiplicativeExpression();

        if ($_success) {
            $left = $this->value;
        }

        if ($_success) {
            $_value26[] = $this->value;

            $_value24 = array();
            $_cut25 = $this->cut;

            while (true) {
                $_position23 = $this->position;

                $this->cut = false;
                $_value22 = array();

                $_success = $this->parse_();

                if ($_success) {
                    $_value22[] = $this->value;

                    $_success = $this->parseADDITIVE_OPERATOR();

                    if ($_success) {
                        $operator = $this->value;
                    }
                }

                if ($_success) {
                    $_value22[] = $this->value;

                    $_success = $this->parse_();
                }

                if ($_success) {
                    $_value22[] = $this->value;

                    $_success = $this->parseMultiplicativeExpression();

                    if ($_success) {
                        $right = $this->value;
                    }
                }

                if ($_success) {
                    $_value22[] = $this->value;

                    $this->value = $_value22;
                }

                if ($_success) {
                    $this->value = call_user_func(function () use (&$left, &$operator, &$right) {
                        $left = new BinaryNode($operator, $left, $right);
                    });
                }

                if (!$_success) {
                    break;
                }

                $_value24[] = $this->value;
            }

            if (!$this->cut) {
                $_success = true;
                $this->position = $_position23;
                $this->value = $_value24;
            }

            $this->cut = $_cut25;
        }

        if ($_success) {
            $_value26[] = $this->value;

            $this->value = $_value26;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$left, &$operator, &$right) {
                return $left;
            });
        }

        $this->cache['AdditiveExpression'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'AdditiveExpression');
        }

        return $_success;
    }

    protected function parseMultiplicativeExpression()
    {
        $_position = $this->position;

        if (isset($this->cache['MultiplicativeExpression'][$_position])) {
            $_success = $this->cache['MultiplicativeExpression'][$_position]['success'];
            $this->position = $this->cache['MultiplicativeExpression'][$_position]['position'];
            $this->value = $this->cache['MultiplicativeExpression'][$_position]['value'];

            return $_success;
        }

        $_success = $this->parseUnaryExpression();

        $this->cache['MultiplicativeExpression'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'MultiplicativeExpression');
        }

        return $_success;
    }

    protected function parseUnaryExpression()
    {
        $_position = $this->position;

        if (isset($this->cache['UnaryExpression'][$_position])) {
            $_success = $this->cache['UnaryExpression'][$_position]['success'];
            $this->position = $this->cache['UnaryExpression'][$_position]['position'];
            $this->value = $this->cache['UnaryExpression'][$_position]['value'];

            return $_success;
        }

        $_success = $this->parsePostfixExpression();

        $this->cache['UnaryExpression'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'UnaryExpression');
        }

        return $_success;
    }

    protected function parsePostfixExpression()
    {
        $_position = $this->position;

        if (isset($this->cache['PostfixExpression'][$_position])) {
            $_success = $this->cache['PostfixExpression'][$_position]['success'];
            $this->position = $this->cache['PostfixExpression'][$_position]['position'];
            $this->value = $this->cache['PostfixExpression'][$_position]['value'];

            return $_success;
        }

        $_value34 = array();

        $_success = $this->parsePrimaryExpression();

        if ($_success) {
            $left = $this->value;
        }

        if ($_success) {
            $_value34[] = $this->value;

            $_value32 = array();
            $_cut33 = $this->cut;

            while (true) {
                $_position31 = $this->position;

                $this->cut = false;
                $_position29 = $this->position;
                $_cut30 = $this->cut;

                $this->cut = false;
                $_value27 = array();

                $_success = $this->parse_();

                if ($_success) {
                    $_value27[] = $this->value;

                    if (substr($this->string, $this->position, strlen("(")) === "(") {
                        $_success = true;
                        $this->value = substr($this->string, $this->position, strlen("("));
                        $this->position += strlen("(");
                    } else {
                        $_success = false;

                        $this->report($this->position, '"("');
                    }
                }

                if ($_success) {
                    $_value27[] = $this->value;

                    $_success = $this->parse_();
                }

                if ($_success) {
                    $_value27[] = $this->value;

                    if (substr($this->string, $this->position, strlen(")")) === ")") {
                        $_success = true;
                        $this->value = substr($this->string, $this->position, strlen(")"));
                        $this->position += strlen(")");
                    } else {
                        $_success = false;

                        $this->report($this->position, '")"');
                    }
                }

                if ($_success) {
                    $_value27[] = $this->value;

                    $this->value = $_value27;
                }

                if ($_success) {
                    $this->value = call_user_func(function () use (&$left) {
                        $left = new CallNode($left, []);
                    });
                }

                if (!$_success && !$this->cut) {
                    $this->position = $_position29;

                    $_value28 = array();

                    $_success = $this->parse_();

                    if ($_success) {
                        $_value28[] = $this->value;

                        if (substr($this->string, $this->position, strlen("(")) === "(") {
                            $_success = true;
                            $this->value = substr($this->string, $this->position, strlen("("));
                            $this->position += strlen("(");
                        } else {
                            $_success = false;

                            $this->report($this->position, '"("');
                        }
                    }

                    if ($_success) {
                        $_value28[] = $this->value;

                        $_success = $this->parse_();
                    }

                    if ($_success) {
                        $_value28[] = $this->value;

                        $_success = $this->parseExpressionList();

                        if ($_success) {
                            $right = $this->value;
                        }
                    }

                    if ($_success) {
                        $_value28[] = $this->value;

                        $_success = $this->parse_();
                    }

                    if ($_success) {
                        $_value28[] = $this->value;

                        if (substr($this->string, $this->position, strlen(")")) === ")") {
                            $_success = true;
                            $this->value = substr($this->string, $this->position, strlen(")"));
                            $this->position += strlen(")");
                        } else {
                            $_success = false;

                            $this->report($this->position, '")"');
                        }
                    }

                    if ($_success) {
                        $_value28[] = $this->value;

                        $this->value = $_value28;
                    }

                    if ($_success) {
                        $this->value = call_user_func(function () use (&$left, &$right) {
                            $left = new CallNode($left, $right);
                        });
                    }
                }

                $this->cut = $_cut30;

                if (!$_success) {
                    break;
                }

                $_value32[] = $this->value;
            }

            if (!$this->cut) {
                $_success = true;
                $this->position = $_position31;
                $this->value = $_value32;
            }

            $this->cut = $_cut33;
        }

        if ($_success) {
            $_value34[] = $this->value;

            $this->value = $_value34;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$left, &$right) {
                return $left;
            });
        }

        $this->cache['PostfixExpression'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'PostfixExpression');
        }

        return $_success;
    }

    protected function parseActionLiteral()
    {
        $_position = $this->position;

        if (isset($this->cache['ActionLiteral'][$_position])) {
            $_success = $this->cache['ActionLiteral'][$_position]['success'];
            $this->position = $this->cache['ActionLiteral'][$_position]['position'];
            $this->value = $this->cache['ActionLiteral'][$_position]['value'];

            return $_success;
        }

        $_value35 = array();

        $_success = $this->parseParameterList();

        if ($_success) {
            $parameters = $this->value;
        }

        if ($_success) {
            $_value35[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value35[] = $this->value;

            if (substr($this->string, $this->position, strlen("=>")) === "=>") {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen("=>"));
                $this->position += strlen("=>");
            } else {
                $_success = false;

                $this->report($this->position, '"=>"');
            }
        }

        if ($_success) {
            $_value35[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value35[] = $this->value;

            if (substr($this->string, $this->position, strlen("{")) === "{") {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen("{"));
                $this->position += strlen("{");
            } else {
                $_success = false;

                $this->report($this->position, '"{"');
            }
        }

        if ($_success) {
            $_value35[] = $this->value;

            $_success = $this->parseStatementList();

            if ($_success) {
                $statements = $this->value;
            }
        }

        if ($_success) {
            $_value35[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value35[] = $this->value;

            if (substr($this->string, $this->position, strlen("}")) === "}") {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen("}"));
                $this->position += strlen("}");
            } else {
                $_success = false;

                $this->report($this->position, '"}"');
            }
        }

        if ($_success) {
            $_value35[] = $this->value;

            $this->value = $_value35;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$parameters, &$statements) {
                return new ActionNode($parameters, $statements);
            });
        }

        $this->cache['ActionLiteral'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'ActionLiteral');
        }

        return $_success;
    }

    protected function parseFunctionLiteral()
    {
        $_position = $this->position;

        if (isset($this->cache['FunctionLiteral'][$_position])) {
            $_success = $this->cache['FunctionLiteral'][$_position]['success'];
            $this->position = $this->cache['FunctionLiteral'][$_position]['position'];
            $this->value = $this->cache['FunctionLiteral'][$_position]['value'];

            return $_success;
        }

        $_value36 = array();

        $_success = $this->parseParameterList();

        if ($_success) {
            $parameters = $this->value;
        }

        if ($_success) {
            $_value36[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value36[] = $this->value;

            if (substr($this->string, $this->position, strlen("->")) === "->") {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen("->"));
                $this->position += strlen("->");
            } else {
                $_success = false;

                $this->report($this->position, '"->"');
            }
        }

        if ($_success) {
            $_value36[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value36[] = $this->value;

            $_success = $this->parseExpression();

            if ($_success) {
                $expression = $this->value;
            }
        }

        if ($_success) {
            $_value36[] = $this->value;

            $this->value = $_value36;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$parameters, &$expression) {
                return new FunctionNode($parameters, $expression);
            });
        }

        $this->cache['FunctionLiteral'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'FunctionLiteral');
        }

        return $_success;
    }

    protected function parsePrimaryExpression()
    {
        $_position = $this->position;

        if (isset($this->cache['PrimaryExpression'][$_position])) {
            $_success = $this->cache['PrimaryExpression'][$_position]['success'];
            $this->position = $this->cache['PrimaryExpression'][$_position]['position'];
            $this->value = $this->cache['PrimaryExpression'][$_position]['value'];

            return $_success;
        }

        $_position37 = $this->position;
        $_cut38 = $this->cut;

        $this->cut = false;
        $_success = $this->parseStringLiteral();

        if (!$_success && !$this->cut) {
            $this->position = $_position37;

            $_success = $this->parseReference();
        }

        $this->cut = $_cut38;

        $this->cache['PrimaryExpression'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'PrimaryExpression');
        }

        return $_success;
    }

    protected function parseStringLiteral()
    {
        $_position = $this->position;

        if (isset($this->cache['StringLiteral'][$_position])) {
            $_success = $this->cache['StringLiteral'][$_position]['success'];
            $this->position = $this->cache['StringLiteral'][$_position]['position'];
            $this->value = $this->cache['StringLiteral'][$_position]['value'];

            return $_success;
        }

        $_position46 = $this->position;

        $_value45 = array();

        if (substr($this->string, $this->position, strlen("\"")) === "\"") {
            $_success = true;
            $this->value = substr($this->string, $this->position, strlen("\""));
            $this->position += strlen("\"");
        } else {
            $_success = false;

            $this->report($this->position, '"\\""');
        }

        if ($_success) {
            $_value45[] = $this->value;

            $_value43 = array();
            $_cut44 = $this->cut;

            while (true) {
                $_position42 = $this->position;

                $this->cut = false;
                $_position40 = $this->position;
                $_cut41 = $this->cut;

                $this->cut = false;
                if (preg_match('/^[^\\\\"]$/', substr($this->string, $this->position, 1))) {
                    $_success = true;
                    $this->value = substr($this->string, $this->position, 1);
                    $this->position += 1;
                } else {
                    $_success = false;
                }

                if (!$_success && !$this->cut) {
                    $this->position = $_position40;

                    $_value39 = array();

                    if (substr($this->string, $this->position, strlen("\\")) === "\\") {
                        $_success = true;
                        $this->value = substr($this->string, $this->position, strlen("\\"));
                        $this->position += strlen("\\");
                    } else {
                        $_success = false;

                        $this->report($this->position, '"\\\\"');
                    }

                    if ($_success) {
                        $_value39[] = $this->value;

                        if ($this->position < strlen($this->string)) {
                            $_success = true;
                            $this->value = substr($this->string, $this->position, 1);
                            $this->position += 1;
                        } else {
                            $_success = false;
                        }
                    }

                    if ($_success) {
                        $_value39[] = $this->value;

                        $this->value = $_value39;
                    }
                }

                $this->cut = $_cut41;

                if (!$_success) {
                    break;
                }

                $_value43[] = $this->value;
            }

            if (!$this->cut) {
                $_success = true;
                $this->position = $_position42;
                $this->value = $_value43;
            }

            $this->cut = $_cut44;
        }

        if ($_success) {
            $_value45[] = $this->value;

            if (substr($this->string, $this->position, strlen("\"")) === "\"") {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen("\""));
                $this->position += strlen("\"");
            } else {
                $_success = false;

                $this->report($this->position, '"\\""');
            }
        }

        if ($_success) {
            $_value45[] = $this->value;

            $this->value = $_value45;
        }

        if ($_success) {
            $this->value = strval(substr($this->string, $_position46, $this->position - $_position46));
        }

        if ($_success) {
            $value = $this->value;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$value) {
                return new StringNode($value);
            });
        }

        $this->cache['StringLiteral'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'StringLiteral');
        }

        return $_success;
    }

    protected function parseReference()
    {
        $_position = $this->position;

        if (isset($this->cache['Reference'][$_position])) {
            $_success = $this->cache['Reference'][$_position]['success'];
            $this->position = $this->cache['Reference'][$_position]['position'];
            $this->value = $this->cache['Reference'][$_position]['value'];

            return $_success;
        }

        $_success = $this->parseIdentifier();

        if ($_success) {
            $value = $this->value;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$value) {
                return new ReferenceNode($value);
            });
        }

        $this->cache['Reference'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'Reference');
        }

        return $_success;
    }

    protected function parse_()
    {
        $_position = $this->position;

        if (isset($this->cache['_'][$_position])) {
            $_success = $this->cache['_'][$_position]['success'];
            $this->position = $this->cache['_'][$_position]['position'];
            $this->value = $this->cache['_'][$_position]['value'];

            return $_success;
        }

        $_value48 = array();
        $_cut49 = $this->cut;

        while (true) {
            $_position47 = $this->position;

            $this->cut = false;
            if (preg_match('/^[\\r\\t\\n ]$/', substr($this->string, $this->position, 1))) {
                $_success = true;
                $this->value = substr($this->string, $this->position, 1);
                $this->position += 1;
            } else {
                $_success = false;
            }

            if (!$_success) {
                break;
            }

            $_value48[] = $this->value;
        }

        if (!$this->cut) {
            $_success = true;
            $this->position = $_position47;
            $this->value = $_value48;
        }

        $this->cut = $_cut49;

        $this->cache['_'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, '_');
        }

        return $_success;
    }

    protected function parseIdentifier()
    {
        $_position = $this->position;

        if (isset($this->cache['Identifier'][$_position])) {
            $_success = $this->cache['Identifier'][$_position]['success'];
            $this->position = $this->cache['Identifier'][$_position]['position'];
            $this->value = $this->cache['Identifier'][$_position]['value'];

            return $_success;
        }

        $_position54 = $this->position;

        $_value53 = array();

        if (preg_match('/^[A-Za-z_]$/', substr($this->string, $this->position, 1))) {
            $_success = true;
            $this->value = substr($this->string, $this->position, 1);
            $this->position += 1;
        } else {
            $_success = false;
        }

        if ($_success) {
            $_value53[] = $this->value;

            $_value51 = array();
            $_cut52 = $this->cut;

            while (true) {
                $_position50 = $this->position;

                $this->cut = false;
                if (preg_match('/^[A-Za-z0-9_]$/', substr($this->string, $this->position, 1))) {
                    $_success = true;
                    $this->value = substr($this->string, $this->position, 1);
                    $this->position += 1;
                } else {
                    $_success = false;
                }

                if (!$_success) {
                    break;
                }

                $_value51[] = $this->value;
            }

            if (!$this->cut) {
                $_success = true;
                $this->position = $_position50;
                $this->value = $_value51;
            }

            $this->cut = $_cut52;
        }

        if ($_success) {
            $_value53[] = $this->value;

            $this->value = $_value53;
        }

        if ($_success) {
            $this->value = strval(substr($this->string, $_position54, $this->position - $_position54));
        }

        $this->cache['Identifier'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'Identifier');
        }

        return $_success;
    }

    protected function parseStatementList()
    {
        $_position = $this->position;

        if (isset($this->cache['StatementList'][$_position])) {
            $_success = $this->cache['StatementList'][$_position]['success'];
            $this->position = $this->cache['StatementList'][$_position]['position'];
            $this->value = $this->cache['StatementList'][$_position]['value'];

            return $_success;
        }

        $_value55 = array();

        $_success = $this->parse_();

        if ($_success) {
            $_value55[] = $this->value;

            if (substr($this->string, $this->position, strlen("STATEMENTS")) === "STATEMENTS") {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen("STATEMENTS"));
                $this->position += strlen("STATEMENTS");
            } else {
                $_success = false;

                $this->report($this->position, '"STATEMENTS"');
            }
        }

        if ($_success) {
            $_value55[] = $this->value;

            $this->value = $_value55;
        }

        if ($_success) {
            $this->value = call_user_func(function () {
                return [];
            });
        }

        $this->cache['StatementList'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'StatementList');
        }

        return $_success;
    }

    protected function parseParameterList()
    {
        $_position = $this->position;

        if (isset($this->cache['ParameterList'][$_position])) {
            $_success = $this->cache['ParameterList'][$_position]['success'];
            $this->position = $this->cache['ParameterList'][$_position]['position'];
            $this->value = $this->cache['ParameterList'][$_position]['value'];

            return $_success;
        }

        $_position58 = $this->position;
        $_cut59 = $this->cut;

        $this->cut = false;
        $_success = $this->parseIdentifier();

        if ($_success) {
            $parameter = $this->value;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$parameter) {
                return [$parameter];
            });
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position58;

            $_value56 = array();

            if (substr($this->string, $this->position, strlen("(")) === "(") {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen("("));
                $this->position += strlen("(");
            } else {
                $_success = false;

                $this->report($this->position, '"("');
            }

            if ($_success) {
                $_value56[] = $this->value;

                $_success = $this->parse_();
            }

            if ($_success) {
                $_value56[] = $this->value;

                if (substr($this->string, $this->position, strlen(")")) === ")") {
                    $_success = true;
                    $this->value = substr($this->string, $this->position, strlen(")"));
                    $this->position += strlen(")");
                } else {
                    $_success = false;

                    $this->report($this->position, '")"');
                }
            }

            if ($_success) {
                $_value56[] = $this->value;

                $this->value = $_value56;
            }

            if ($_success) {
                $this->value = call_user_func(function () use (&$parameter) {
                    return [];
                });
            }
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position58;

            $_value57 = array();

            if (substr($this->string, $this->position, strlen("(")) === "(") {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen("("));
                $this->position += strlen("(");
            } else {
                $_success = false;

                $this->report($this->position, '"("');
            }

            if ($_success) {
                $_value57[] = $this->value;

                $_success = $this->parse_();
            }

            if ($_success) {
                $_value57[] = $this->value;

                $_success = $this->parseIdentifierList();

                if ($_success) {
                    $parameters = $this->value;
                }
            }

            if ($_success) {
                $_value57[] = $this->value;

                $_success = $this->parse_();
            }

            if ($_success) {
                $_value57[] = $this->value;

                if (substr($this->string, $this->position, strlen(")")) === ")") {
                    $_success = true;
                    $this->value = substr($this->string, $this->position, strlen(")"));
                    $this->position += strlen(")");
                } else {
                    $_success = false;

                    $this->report($this->position, '")"');
                }
            }

            if ($_success) {
                $_value57[] = $this->value;

                $this->value = $_value57;
            }

            if ($_success) {
                $this->value = call_user_func(function () use (&$parameter, &$parameters) {
                    return $parameters;
                });
            }
        }

        $this->cut = $_cut59;

        $this->cache['ParameterList'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'ParameterList');
        }

        return $_success;
    }

    protected function parseArgumentList()
    {
        $_position = $this->position;

        if (isset($this->cache['ArgumentList'][$_position])) {
            $_success = $this->cache['ArgumentList'][$_position]['success'];
            $this->position = $this->cache['ArgumentList'][$_position]['position'];
            $this->value = $this->cache['ArgumentList'][$_position]['value'];

            return $_success;
        }

        $_position62 = $this->position;
        $_cut63 = $this->cut;

        $this->cut = false;
        $_success = $this->parseExpression();

        if ($_success) {
            $argument = $this->value;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$argument) {
                return [$argument];
            });
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position62;

            $_value60 = array();

            if (substr($this->string, $this->position, strlen("(")) === "(") {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen("("));
                $this->position += strlen("(");
            } else {
                $_success = false;

                $this->report($this->position, '"("');
            }

            if ($_success) {
                $_value60[] = $this->value;

                $_success = $this->parse_();
            }

            if ($_success) {
                $_value60[] = $this->value;

                if (substr($this->string, $this->position, strlen(")")) === ")") {
                    $_success = true;
                    $this->value = substr($this->string, $this->position, strlen(")"));
                    $this->position += strlen(")");
                } else {
                    $_success = false;

                    $this->report($this->position, '")"');
                }
            }

            if ($_success) {
                $_value60[] = $this->value;

                $this->value = $_value60;
            }

            if ($_success) {
                $this->value = call_user_func(function () use (&$argument) {
                    return [];
                });
            }
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position62;

            $_value61 = array();

            if (substr($this->string, $this->position, strlen("(")) === "(") {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen("("));
                $this->position += strlen("(");
            } else {
                $_success = false;

                $this->report($this->position, '"("');
            }

            if ($_success) {
                $_value61[] = $this->value;

                $_success = $this->parse_();
            }

            if ($_success) {
                $_value61[] = $this->value;

                $_success = $this->parseExpressionList();

                if ($_success) {
                    $arguments = $this->value;
                }
            }

            if ($_success) {
                $_value61[] = $this->value;

                $_success = $this->parse_();
            }

            if ($_success) {
                $_value61[] = $this->value;

                if (substr($this->string, $this->position, strlen(")")) === ")") {
                    $_success = true;
                    $this->value = substr($this->string, $this->position, strlen(")"));
                    $this->position += strlen(")");
                } else {
                    $_success = false;

                    $this->report($this->position, '")"');
                }
            }

            if ($_success) {
                $_value61[] = $this->value;

                $this->value = $_value61;
            }

            if ($_success) {
                $this->value = call_user_func(function () use (&$argument, &$arguments) {
                    return $arguments;
                });
            }
        }

        $this->cut = $_cut63;

        $this->cache['ArgumentList'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'ArgumentList');
        }

        return $_success;
    }

    protected function parseIdentifierList()
    {
        $_position = $this->position;

        if (isset($this->cache['IdentifierList'][$_position])) {
            $_success = $this->cache['IdentifierList'][$_position]['success'];
            $this->position = $this->cache['IdentifierList'][$_position]['position'];
            $this->value = $this->cache['IdentifierList'][$_position]['value'];

            return $_success;
        }

        $_value68 = array();

        $_success = $this->parseIdentifier();

        if ($_success) {
            $first = $this->value;
        }

        if ($_success) {
            $_value68[] = $this->value;

            $_value66 = array();
            $_cut67 = $this->cut;

            while (true) {
                $_position65 = $this->position;

                $this->cut = false;
                $_value64 = array();

                $_success = $this->parse_();

                if ($_success) {
                    $_value64[] = $this->value;

                    if (substr($this->string, $this->position, strlen(",")) === ",") {
                        $_success = true;
                        $this->value = substr($this->string, $this->position, strlen(","));
                        $this->position += strlen(",");
                    } else {
                        $_success = false;

                        $this->report($this->position, '","');
                    }
                }

                if ($_success) {
                    $_value64[] = $this->value;

                    $_success = $this->parse_();
                }

                if ($_success) {
                    $_value64[] = $this->value;

                    $_success = $this->parseIdentifier();

                    if ($_success) {
                        $next = $this->value;
                    }
                }

                if ($_success) {
                    $_value64[] = $this->value;

                    $this->value = $_value64;
                }

                if ($_success) {
                    $this->value = call_user_func(function () use (&$first, &$next) {
                        return $next;
                    });
                }

                if (!$_success) {
                    break;
                }

                $_value66[] = $this->value;
            }

            if (!$this->cut) {
                $_success = true;
                $this->position = $_position65;
                $this->value = $_value66;
            }

            $this->cut = $_cut67;

            if ($_success) {
                $rest = $this->value;
            }
        }

        if ($_success) {
            $_value68[] = $this->value;

            $this->value = $_value68;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$first, &$next, &$rest) {
                return array_merge([$first], $rest);
            });
        }

        $this->cache['IdentifierList'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'IdentifierList');
        }

        return $_success;
    }

    protected function parseExpressionList()
    {
        $_position = $this->position;

        if (isset($this->cache['ExpressionList'][$_position])) {
            $_success = $this->cache['ExpressionList'][$_position]['success'];
            $this->position = $this->cache['ExpressionList'][$_position]['position'];
            $this->value = $this->cache['ExpressionList'][$_position]['value'];

            return $_success;
        }

        $_value73 = array();

        $_success = $this->parseExpression();

        if ($_success) {
            $first = $this->value;
        }

        if ($_success) {
            $_value73[] = $this->value;

            $_value71 = array();
            $_cut72 = $this->cut;

            while (true) {
                $_position70 = $this->position;

                $this->cut = false;
                $_value69 = array();

                $_success = $this->parse_();

                if ($_success) {
                    $_value69[] = $this->value;

                    if (substr($this->string, $this->position, strlen(",")) === ",") {
                        $_success = true;
                        $this->value = substr($this->string, $this->position, strlen(","));
                        $this->position += strlen(",");
                    } else {
                        $_success = false;

                        $this->report($this->position, '","');
                    }
                }

                if ($_success) {
                    $_value69[] = $this->value;

                    $_success = $this->parse_();
                }

                if ($_success) {
                    $_value69[] = $this->value;

                    $_success = $this->parseExpression();

                    if ($_success) {
                        $next = $this->value;
                    }
                }

                if ($_success) {
                    $_value69[] = $this->value;

                    $this->value = $_value69;
                }

                if ($_success) {
                    $this->value = call_user_func(function () use (&$first, &$next) {
                        return $next;
                    });
                }

                if (!$_success) {
                    break;
                }

                $_value71[] = $this->value;
            }

            if (!$this->cut) {
                $_success = true;
                $this->position = $_position70;
                $this->value = $_value71;
            }

            $this->cut = $_cut72;

            if ($_success) {
                $rest = $this->value;
            }
        }

        if ($_success) {
            $_value73[] = $this->value;

            $this->value = $_value73;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$first, &$next, &$rest) {
                return array_merge([$first], $rest);
            });
        }

        $this->cache['ExpressionList'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'ExpressionList');
        }

        return $_success;
    }

    private function line()
    {
        if (!empty($this->errors)) {
            $positions = array_keys($this->errors);
        } else {
            $positions = array_keys($this->warnings);
        }

        return count(explode("\n", substr($this->string, 0, max($positions))));
    }

    private function rest()
    {
        return '"' . substr($this->string, $this->position) . '"';
    }

    protected function report($position, $expecting)
    {
        if ($this->cut) {
            $this->errors[$position][] = $expecting;
        } else {
            $this->warnings[$position][] = $expecting;
        }
    }

    private function expecting()
    {
        if (!empty($this->errors)) {
            ksort($this->errors);

            return end($this->errors)[0];
        }

        ksort($this->warnings);

        return implode(', ', end($this->warnings));
    }

    public function parse($_string)
    {
        $this->string = $_string;
        $this->position = 0;
        $this->value = null;
        $this->cache = array();
        $this->cut = false;
        $this->errors = array();
        $this->warnings = array();

        $_success = $this->parseExpression();

        if ($_success && $this->position < strlen($this->string)) {
            $_success = false;

            $this->report($this->position, "end of file");
        }

        if (!$_success) {
            throw new \InvalidArgumentException("Syntax error, expecting {$this->expecting()} on line {$this->line()}");
        }

        return $this->value;
    }
}