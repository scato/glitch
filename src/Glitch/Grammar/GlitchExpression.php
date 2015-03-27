<?php

namespace Glitch\Grammar;

use Glitch\Grammar\Tree\ActionNode;
use Glitch\Grammar\Tree\BinaryNode;
use Glitch\Grammar\Tree\CallNode;
use Glitch\Grammar\Tree\FunctionNode;
use Glitch\Grammar\Tree\ReferenceNode;
use Glitch\Grammar\Tree\StringNode;

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

            $_success = $this->parseEqualityExpression();
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

    protected function parseEQUALITY_OPERATOR()
    {
        $_position = $this->position;

        if (isset($this->cache['EQUALITY_OPERATOR'][$_position])) {
            $_success = $this->cache['EQUALITY_OPERATOR'][$_position]['success'];
            $this->position = $this->cache['EQUALITY_OPERATOR'][$_position]['position'];
            $this->value = $this->cache['EQUALITY_OPERATOR'][$_position]['value'];

            return $_success;
        }

        $_position3 = $this->position;
        $_cut4 = $this->cut;

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
            $this->position = $_position3;

            if (substr($this->string, $this->position, strlen("!==")) === "!==") {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen("!=="));
                $this->position += strlen("!==");
            } else {
                $_success = false;

                $this->report($this->position, '"!=="');
            }
        }

        $this->cut = $_cut4;

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

        $_value9 = array();

        $_success = $this->parseRelationalExpression();

        if ($_success) {
            $left = $this->value;
        }

        if ($_success) {
            $_value9[] = $this->value;

            $_value7 = array();
            $_cut8 = $this->cut;

            while (true) {
                $_position6 = $this->position;

                $this->cut = false;
                $_value5 = array();

                $_success = $this->parse_();

                if ($_success) {
                    $_value5[] = $this->value;

                    $_success = $this->parseEQUALITY_OPERATOR();

                    if ($_success) {
                        $operator = $this->value;
                    }
                }

                if ($_success) {
                    $_value5[] = $this->value;

                    $_success = $this->parse_();
                }

                if ($_success) {
                    $_value5[] = $this->value;

                    $_success = $this->parseRelationalExpression();

                    if ($_success) {
                        $right = $this->value;
                    }
                }

                if ($_success) {
                    $_value5[] = $this->value;

                    $this->value = $_value5;
                }

                if ($_success) {
                    $this->value = call_user_func(function () use (&$left, &$operator, &$right) {
                        $left = new BinaryNode($operator, $left, $right);
                    });
                }

                if (!$_success) {
                    break;
                }

                $_value7[] = $this->value;
            }

            if (!$this->cut) {
                $_success = true;
                $this->position = $_position6;
                $this->value = $_value7;
            }

            $this->cut = $_cut8;
        }

        if ($_success) {
            $_value9[] = $this->value;

            $this->value = $_value9;
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

        $_position10 = $this->position;
        $_cut11 = $this->cut;

        $this->cut = false;
        if (substr($this->string, $this->position, strlen("<")) === "<") {
            $_success = true;
            $this->value = substr($this->string, $this->position, strlen("<"));
            $this->position += strlen("<");
        } else {
            $_success = false;

            $this->report($this->position, '"<"');
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position10;

            if (substr($this->string, $this->position, strlen(">")) === ">") {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen(">"));
                $this->position += strlen(">");
            } else {
                $_success = false;

                $this->report($this->position, '">"');
            }
        }

        $this->cut = $_cut11;

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

        $_value16 = array();

        $_success = $this->parseAdditiveExpression();

        if ($_success) {
            $left = $this->value;
        }

        if ($_success) {
            $_value16[] = $this->value;

            $_value14 = array();
            $_cut15 = $this->cut;

            while (true) {
                $_position13 = $this->position;

                $this->cut = false;
                $_value12 = array();

                $_success = $this->parse_();

                if ($_success) {
                    $_value12[] = $this->value;

                    $_success = $this->parseRELATIONAL_OPERATOR();

                    if ($_success) {
                        $operator = $this->value;
                    }
                }

                if ($_success) {
                    $_value12[] = $this->value;

                    $_success = $this->parse_();
                }

                if ($_success) {
                    $_value12[] = $this->value;

                    $_success = $this->parseAdditiveExpression();

                    if ($_success) {
                        $right = $this->value;
                    }
                }

                if ($_success) {
                    $_value12[] = $this->value;

                    $this->value = $_value12;
                }

                if ($_success) {
                    $this->value = call_user_func(function () use (&$left, &$operator, &$right) {
                        $left = new BinaryNode($operator, $left, $right);
                    });
                }

                if (!$_success) {
                    break;
                }

                $_value14[] = $this->value;
            }

            if (!$this->cut) {
                $_success = true;
                $this->position = $_position13;
                $this->value = $_value14;
            }

            $this->cut = $_cut15;
        }

        if ($_success) {
            $_value16[] = $this->value;

            $this->value = $_value16;
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

        $_position17 = $this->position;
        $_cut18 = $this->cut;

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
            $this->position = $_position17;

            if (substr($this->string, $this->position, strlen("-")) === "-") {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen("-"));
                $this->position += strlen("-");
            } else {
                $_success = false;

                $this->report($this->position, '"-"');
            }
        }

        $this->cut = $_cut18;

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

        $_value23 = array();

        $_success = $this->parseMultiplicativeExpression();

        if ($_success) {
            $left = $this->value;
        }

        if ($_success) {
            $_value23[] = $this->value;

            $_value21 = array();
            $_cut22 = $this->cut;

            while (true) {
                $_position20 = $this->position;

                $this->cut = false;
                $_value19 = array();

                $_success = $this->parse_();

                if ($_success) {
                    $_value19[] = $this->value;

                    $_success = $this->parseADDITIVE_OPERATOR();

                    if ($_success) {
                        $operator = $this->value;
                    }
                }

                if ($_success) {
                    $_value19[] = $this->value;

                    $_success = $this->parse_();
                }

                if ($_success) {
                    $_value19[] = $this->value;

                    $_success = $this->parseMultiplicativeExpression();

                    if ($_success) {
                        $right = $this->value;
                    }
                }

                if ($_success) {
                    $_value19[] = $this->value;

                    $this->value = $_value19;
                }

                if ($_success) {
                    $this->value = call_user_func(function () use (&$left, &$operator, &$right) {
                        $left = new BinaryNode($operator, $left, $right);
                    });
                }

                if (!$_success) {
                    break;
                }

                $_value21[] = $this->value;
            }

            if (!$this->cut) {
                $_success = true;
                $this->position = $_position20;
                $this->value = $_value21;
            }

            $this->cut = $_cut22;
        }

        if ($_success) {
            $_value23[] = $this->value;

            $this->value = $_value23;
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

        $_value31 = array();

        $_success = $this->parsePrimaryExpression();

        if ($_success) {
            $left = $this->value;
        }

        if ($_success) {
            $_value31[] = $this->value;

            $_value29 = array();
            $_cut30 = $this->cut;

            while (true) {
                $_position28 = $this->position;

                $this->cut = false;
                $_position26 = $this->position;
                $_cut27 = $this->cut;

                $this->cut = false;
                $_value24 = array();

                $_success = $this->parse_();

                if ($_success) {
                    $_value24[] = $this->value;

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
                    $_value24[] = $this->value;

                    $_success = $this->parse_();
                }

                if ($_success) {
                    $_value24[] = $this->value;

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
                    $_value24[] = $this->value;

                    $this->value = $_value24;
                }

                if ($_success) {
                    $this->value = call_user_func(function () use (&$left) {
                        $left = new CallNode($left, []);
                    });
                }

                if (!$_success && !$this->cut) {
                    $this->position = $_position26;

                    $_value25 = array();

                    $_success = $this->parse_();

                    if ($_success) {
                        $_value25[] = $this->value;

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
                        $_value25[] = $this->value;

                        $_success = $this->parse_();
                    }

                    if ($_success) {
                        $_value25[] = $this->value;

                        $_success = $this->parseExpressionList();

                        if ($_success) {
                            $right = $this->value;
                        }
                    }

                    if ($_success) {
                        $_value25[] = $this->value;

                        $_success = $this->parse_();
                    }

                    if ($_success) {
                        $_value25[] = $this->value;

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
                        $_value25[] = $this->value;

                        $this->value = $_value25;
                    }

                    if ($_success) {
                        $this->value = call_user_func(function () use (&$left, &$right) {
                            $left = new CallNode($left, $right);
                        });
                    }
                }

                $this->cut = $_cut27;

                if (!$_success) {
                    break;
                }

                $_value29[] = $this->value;
            }

            if (!$this->cut) {
                $_success = true;
                $this->position = $_position28;
                $this->value = $_value29;
            }

            $this->cut = $_cut30;
        }

        if ($_success) {
            $_value31[] = $this->value;

            $this->value = $_value31;
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

        $_value32 = array();

        $_success = $this->parseParameterList();

        if ($_success) {
            $parameters = $this->value;
        }

        if ($_success) {
            $_value32[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value32[] = $this->value;

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
            $_value32[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value32[] = $this->value;

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
            $_value32[] = $this->value;

            $_success = $this->parseStatementList();

            if ($_success) {
                $statements = $this->value;
            }
        }

        if ($_success) {
            $_value32[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value32[] = $this->value;

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
            $_value32[] = $this->value;

            $this->value = $_value32;
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

        $_value33 = array();

        $_success = $this->parseParameterList();

        if ($_success) {
            $parameters = $this->value;
        }

        if ($_success) {
            $_value33[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value33[] = $this->value;

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
            $_value33[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value33[] = $this->value;

            $_success = $this->parseExpression();

            if ($_success) {
                $expression = $this->value;
            }
        }

        if ($_success) {
            $_value33[] = $this->value;

            $this->value = $_value33;
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

        $_position34 = $this->position;
        $_cut35 = $this->cut;

        $this->cut = false;
        $_success = $this->parseStringLiteral();

        if (!$_success && !$this->cut) {
            $this->position = $_position34;

            $_success = $this->parseReference();
        }

        $this->cut = $_cut35;

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

        $_position43 = $this->position;

        $_value42 = array();

        if (substr($this->string, $this->position, strlen("\"")) === "\"") {
            $_success = true;
            $this->value = substr($this->string, $this->position, strlen("\""));
            $this->position += strlen("\"");
        } else {
            $_success = false;

            $this->report($this->position, '"\\""');
        }

        if ($_success) {
            $_value42[] = $this->value;

            $_value40 = array();
            $_cut41 = $this->cut;

            while (true) {
                $_position39 = $this->position;

                $this->cut = false;
                $_position37 = $this->position;
                $_cut38 = $this->cut;

                $this->cut = false;
                if (preg_match('/^[^\\\\"]$/', substr($this->string, $this->position, 1))) {
                    $_success = true;
                    $this->value = substr($this->string, $this->position, 1);
                    $this->position += 1;
                } else {
                    $_success = false;
                }

                if (!$_success && !$this->cut) {
                    $this->position = $_position37;

                    $_value36 = array();

                    if (substr($this->string, $this->position, strlen("\\")) === "\\") {
                        $_success = true;
                        $this->value = substr($this->string, $this->position, strlen("\\"));
                        $this->position += strlen("\\");
                    } else {
                        $_success = false;

                        $this->report($this->position, '"\\\\"');
                    }

                    if ($_success) {
                        $_value36[] = $this->value;

                        if ($this->position < strlen($this->string)) {
                            $_success = true;
                            $this->value = substr($this->string, $this->position, 1);
                            $this->position += 1;
                        } else {
                            $_success = false;
                        }
                    }

                    if ($_success) {
                        $_value36[] = $this->value;

                        $this->value = $_value36;
                    }
                }

                $this->cut = $_cut38;

                if (!$_success) {
                    break;
                }

                $_value40[] = $this->value;
            }

            if (!$this->cut) {
                $_success = true;
                $this->position = $_position39;
                $this->value = $_value40;
            }

            $this->cut = $_cut41;
        }

        if ($_success) {
            $_value42[] = $this->value;

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
            $_value42[] = $this->value;

            $this->value = $_value42;
        }

        if ($_success) {
            $this->value = strval(substr($this->string, $_position43, $this->position - $_position43));
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

        $_value45 = array();
        $_cut46 = $this->cut;

        while (true) {
            $_position44 = $this->position;

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

            $_value45[] = $this->value;
        }

        if (!$this->cut) {
            $_success = true;
            $this->position = $_position44;
            $this->value = $_value45;
        }

        $this->cut = $_cut46;

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

        $_position51 = $this->position;

        $_value50 = array();

        if (preg_match('/^[A-Za-z_]$/', substr($this->string, $this->position, 1))) {
            $_success = true;
            $this->value = substr($this->string, $this->position, 1);
            $this->position += 1;
        } else {
            $_success = false;
        }

        if ($_success) {
            $_value50[] = $this->value;

            $_value48 = array();
            $_cut49 = $this->cut;

            while (true) {
                $_position47 = $this->position;

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

                $_value48[] = $this->value;
            }

            if (!$this->cut) {
                $_success = true;
                $this->position = $_position47;
                $this->value = $_value48;
            }

            $this->cut = $_cut49;
        }

        if ($_success) {
            $_value50[] = $this->value;

            $this->value = $_value50;
        }

        if ($_success) {
            $this->value = strval(substr($this->string, $_position51, $this->position - $_position51));
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

        $_value52 = array();

        $_success = $this->parse_();

        if ($_success) {
            $_value52[] = $this->value;

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
            $_value52[] = $this->value;

            $this->value = $_value52;
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

        $_position55 = $this->position;
        $_cut56 = $this->cut;

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
            $this->position = $_position55;

            $_value53 = array();

            if (substr($this->string, $this->position, strlen("(")) === "(") {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen("("));
                $this->position += strlen("(");
            } else {
                $_success = false;

                $this->report($this->position, '"("');
            }

            if ($_success) {
                $_value53[] = $this->value;

                $_success = $this->parse_();
            }

            if ($_success) {
                $_value53[] = $this->value;

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
                $_value53[] = $this->value;

                $this->value = $_value53;
            }

            if ($_success) {
                $this->value = call_user_func(function () use (&$parameter) {
                    return [];
                });
            }
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position55;

            $_value54 = array();

            if (substr($this->string, $this->position, strlen("(")) === "(") {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen("("));
                $this->position += strlen("(");
            } else {
                $_success = false;

                $this->report($this->position, '"("');
            }

            if ($_success) {
                $_value54[] = $this->value;

                $_success = $this->parse_();
            }

            if ($_success) {
                $_value54[] = $this->value;

                $_success = $this->parseIdentifierList();

                if ($_success) {
                    $parameters = $this->value;
                }
            }

            if ($_success) {
                $_value54[] = $this->value;

                $_success = $this->parse_();
            }

            if ($_success) {
                $_value54[] = $this->value;

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
                $_value54[] = $this->value;

                $this->value = $_value54;
            }

            if ($_success) {
                $this->value = call_user_func(function () use (&$parameter, &$parameters) {
                    return $parameters;
                });
            }
        }

        $this->cut = $_cut56;

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

        $_position59 = $this->position;
        $_cut60 = $this->cut;

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
            $this->position = $_position59;

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
                $this->value = call_user_func(function () use (&$argument) {
                    return [];
                });
            }
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position59;

            $_value58 = array();

            if (substr($this->string, $this->position, strlen("(")) === "(") {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen("("));
                $this->position += strlen("(");
            } else {
                $_success = false;

                $this->report($this->position, '"("');
            }

            if ($_success) {
                $_value58[] = $this->value;

                $_success = $this->parse_();
            }

            if ($_success) {
                $_value58[] = $this->value;

                $_success = $this->parseExpressionList();

                if ($_success) {
                    $arguments = $this->value;
                }
            }

            if ($_success) {
                $_value58[] = $this->value;

                $_success = $this->parse_();
            }

            if ($_success) {
                $_value58[] = $this->value;

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
                $_value58[] = $this->value;

                $this->value = $_value58;
            }

            if ($_success) {
                $this->value = call_user_func(function () use (&$argument, &$arguments) {
                    return $arguments;
                });
            }
        }

        $this->cut = $_cut60;

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

        $_value65 = array();

        $_success = $this->parseIdentifier();

        if ($_success) {
            $first = $this->value;
        }

        if ($_success) {
            $_value65[] = $this->value;

            $_value63 = array();
            $_cut64 = $this->cut;

            while (true) {
                $_position62 = $this->position;

                $this->cut = false;
                $_value61 = array();

                $_success = $this->parse_();

                if ($_success) {
                    $_value61[] = $this->value;

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
                    $_value61[] = $this->value;

                    $_success = $this->parse_();
                }

                if ($_success) {
                    $_value61[] = $this->value;

                    $_success = $this->parseIdentifier();

                    if ($_success) {
                        $next = $this->value;
                    }
                }

                if ($_success) {
                    $_value61[] = $this->value;

                    $this->value = $_value61;
                }

                if ($_success) {
                    $this->value = call_user_func(function () use (&$first, &$next) {
                        return $next;
                    });
                }

                if (!$_success) {
                    break;
                }

                $_value63[] = $this->value;
            }

            if (!$this->cut) {
                $_success = true;
                $this->position = $_position62;
                $this->value = $_value63;
            }

            $this->cut = $_cut64;

            if ($_success) {
                $rest = $this->value;
            }
        }

        if ($_success) {
            $_value65[] = $this->value;

            $this->value = $_value65;
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

        $_value70 = array();

        $_success = $this->parseExpression();

        if ($_success) {
            $first = $this->value;
        }

        if ($_success) {
            $_value70[] = $this->value;

            $_value68 = array();
            $_cut69 = $this->cut;

            while (true) {
                $_position67 = $this->position;

                $this->cut = false;
                $_value66 = array();

                $_success = $this->parse_();

                if ($_success) {
                    $_value66[] = $this->value;

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
                    $_value66[] = $this->value;

                    $_success = $this->parse_();
                }

                if ($_success) {
                    $_value66[] = $this->value;

                    $_success = $this->parseExpression();

                    if ($_success) {
                        $next = $this->value;
                    }
                }

                if ($_success) {
                    $_value66[] = $this->value;

                    $this->value = $_value66;
                }

                if ($_success) {
                    $this->value = call_user_func(function () use (&$first, &$next) {
                        return $next;
                    });
                }

                if (!$_success) {
                    break;
                }

                $_value68[] = $this->value;
            }

            if (!$this->cut) {
                $_success = true;
                $this->position = $_position67;
                $this->value = $_value68;
            }

            $this->cut = $_cut69;

            if ($_success) {
                $rest = $this->value;
            }
        }

        if ($_success) {
            $_value70[] = $this->value;

            $this->value = $_value70;
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
        return count(explode("\n", substr($this->string, 0, $this->position)));
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

        if (!$_success) {
            throw new \InvalidArgumentException("Syntax error, expecting {$this->expecting()} on line {$this->line()}");
        }

        if ($this->position < strlen($this->string)) {
            throw new \InvalidArgumentException("Syntax error, unexpected {$this->rest()} on line {$this->line()}");
        }

        return $this->value;
    }
}