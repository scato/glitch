<?php

namespace Glitch\Grammar;

use Glitch\Grammar\Tree\ActionNode;
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

            $_success = $this->parseCallExpression();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position1;

            $_success = $this->parseTerminal();
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

    protected function parseCallExpression()
    {
        $_position = $this->position;

        if (isset($this->cache['CallExpression'][$_position])) {
            $_success = $this->cache['CallExpression'][$_position]['success'];
            $this->position = $this->cache['CallExpression'][$_position]['position'];
            $this->value = $this->cache['CallExpression'][$_position]['value'];

            return $_success;
        }

        $_position5 = $this->position;
        $_cut6 = $this->cut;

        $this->cut = false;
        $_value3 = array();

        $_success = $this->parsePrimaryExpression();

        if ($_success) {
            $left = $this->value;
        }

        if ($_success) {
            $_value3[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value3[] = $this->value;

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
            $_value3[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value3[] = $this->value;

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
            $_value3[] = $this->value;

            $this->value = $_value3;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$left) {
                return new CallNode($left, []);
            });
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position5;

            $_value4 = array();

            $_success = $this->parsePrimaryExpression();

            if ($_success) {
                $left = $this->value;
            }

            if ($_success) {
                $_value4[] = $this->value;

                $_success = $this->parse_();
            }

            if ($_success) {
                $_value4[] = $this->value;

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
                $_value4[] = $this->value;

                $_success = $this->parse_();
            }

            if ($_success) {
                $_value4[] = $this->value;

                $_success = $this->parseExpressionList();

                if ($_success) {
                    $right = $this->value;
                }
            }

            if ($_success) {
                $_value4[] = $this->value;

                $_success = $this->parse_();
            }

            if ($_success) {
                $_value4[] = $this->value;

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
                $_value4[] = $this->value;

                $this->value = $_value4;
            }

            if ($_success) {
                $this->value = call_user_func(function () use (&$left, &$left, &$right) {
                    return new CallNode($left, $right);
                });
            }
        }

        $this->cut = $_cut6;

        $this->cache['CallExpression'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'CallExpression');
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

        $_value7 = array();

        $_success = $this->parseParameterList();

        if ($_success) {
            $parameters = $this->value;
        }

        if ($_success) {
            $_value7[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value7[] = $this->value;

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
            $_value7[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value7[] = $this->value;

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
            $_value7[] = $this->value;

            $_success = $this->parseStatementList();

            if ($_success) {
                $statements = $this->value;
            }
        }

        if ($_success) {
            $_value7[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value7[] = $this->value;

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
            $_value7[] = $this->value;

            $this->value = $_value7;
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

        $_value8 = array();

        $_success = $this->parseParameterList();

        if ($_success) {
            $parameters = $this->value;
        }

        if ($_success) {
            $_value8[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value8[] = $this->value;

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
            $_value8[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value8[] = $this->value;

            $_success = $this->parseExpression();

            if ($_success) {
                $expression = $this->value;
            }
        }

        if ($_success) {
            $_value8[] = $this->value;

            $this->value = $_value8;
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

        $_success = $this->parseTerminal();

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

    protected function parseTerminal()
    {
        $_position = $this->position;

        if (isset($this->cache['Terminal'][$_position])) {
            $_success = $this->cache['Terminal'][$_position]['success'];
            $this->position = $this->cache['Terminal'][$_position]['position'];
            $this->value = $this->cache['Terminal'][$_position]['value'];

            return $_success;
        }

        $_position9 = $this->position;
        $_cut10 = $this->cut;

        $this->cut = false;
        $_success = $this->parseStringLiteral();

        if (!$_success && !$this->cut) {
            $this->position = $_position9;

            $_success = $this->parseReference();
        }

        $this->cut = $_cut10;

        $this->cache['Terminal'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'Terminal');
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

        $_position18 = $this->position;

        $_value17 = array();

        if (substr($this->string, $this->position, strlen("\"")) === "\"") {
            $_success = true;
            $this->value = substr($this->string, $this->position, strlen("\""));
            $this->position += strlen("\"");
        } else {
            $_success = false;

            $this->report($this->position, '"\\""');
        }

        if ($_success) {
            $_value17[] = $this->value;

            $_value15 = array();
            $_cut16 = $this->cut;

            while (true) {
                $_position14 = $this->position;

                $this->cut = false;
                $_position12 = $this->position;
                $_cut13 = $this->cut;

                $this->cut = false;
                if (preg_match('/^[^\\\\"]$/', substr($this->string, $this->position, 1))) {
                    $_success = true;
                    $this->value = substr($this->string, $this->position, 1);
                    $this->position += 1;
                } else {
                    $_success = false;
                }

                if (!$_success && !$this->cut) {
                    $this->position = $_position12;

                    $_value11 = array();

                    if (substr($this->string, $this->position, strlen("\\")) === "\\") {
                        $_success = true;
                        $this->value = substr($this->string, $this->position, strlen("\\"));
                        $this->position += strlen("\\");
                    } else {
                        $_success = false;

                        $this->report($this->position, '"\\\\"');
                    }

                    if ($_success) {
                        $_value11[] = $this->value;

                        if ($this->position < strlen($this->string)) {
                            $_success = true;
                            $this->value = substr($this->string, $this->position, 1);
                            $this->position += 1;
                        } else {
                            $_success = false;
                        }
                    }

                    if ($_success) {
                        $_value11[] = $this->value;

                        $this->value = $_value11;
                    }
                }

                $this->cut = $_cut13;

                if (!$_success) {
                    break;
                }

                $_value15[] = $this->value;
            }

            if (!$this->cut) {
                $_success = true;
                $this->position = $_position14;
                $this->value = $_value15;
            }

            $this->cut = $_cut16;
        }

        if ($_success) {
            $_value17[] = $this->value;

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
            $_value17[] = $this->value;

            $this->value = $_value17;
        }

        if ($_success) {
            $this->value = strval(substr($this->string, $_position18, $this->position - $_position18));
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

        $_value20 = array();
        $_cut21 = $this->cut;

        while (true) {
            $_position19 = $this->position;

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

            $_value20[] = $this->value;
        }

        if (!$this->cut) {
            $_success = true;
            $this->position = $_position19;
            $this->value = $_value20;
        }

        $this->cut = $_cut21;

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

        $_position26 = $this->position;

        $_value25 = array();

        if (preg_match('/^[A-Za-z_]$/', substr($this->string, $this->position, 1))) {
            $_success = true;
            $this->value = substr($this->string, $this->position, 1);
            $this->position += 1;
        } else {
            $_success = false;
        }

        if ($_success) {
            $_value25[] = $this->value;

            $_value23 = array();
            $_cut24 = $this->cut;

            while (true) {
                $_position22 = $this->position;

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

                $_value23[] = $this->value;
            }

            if (!$this->cut) {
                $_success = true;
                $this->position = $_position22;
                $this->value = $_value23;
            }

            $this->cut = $_cut24;
        }

        if ($_success) {
            $_value25[] = $this->value;

            $this->value = $_value25;
        }

        if ($_success) {
            $this->value = strval(substr($this->string, $_position26, $this->position - $_position26));
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

        $_value27 = array();

        $_success = $this->parse_();

        if ($_success) {
            $_value27[] = $this->value;

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
            $_value27[] = $this->value;

            $this->value = $_value27;
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

        $_position30 = $this->position;
        $_cut31 = $this->cut;

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
            $this->position = $_position30;

            $_value28 = array();

            if (substr($this->string, $this->position, strlen("(")) === "(") {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen("("));
                $this->position += strlen("(");
            } else {
                $_success = false;

                $this->report($this->position, '"("');
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
                $this->value = call_user_func(function () use (&$parameter) {
                    return [];
                });
            }
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position30;

            $_value29 = array();

            if (substr($this->string, $this->position, strlen("(")) === "(") {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen("("));
                $this->position += strlen("(");
            } else {
                $_success = false;

                $this->report($this->position, '"("');
            }

            if ($_success) {
                $_value29[] = $this->value;

                $_success = $this->parse_();
            }

            if ($_success) {
                $_value29[] = $this->value;

                $_success = $this->parseIdentifierList();

                if ($_success) {
                    $parameters = $this->value;
                }
            }

            if ($_success) {
                $_value29[] = $this->value;

                $_success = $this->parse_();
            }

            if ($_success) {
                $_value29[] = $this->value;

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
                $_value29[] = $this->value;

                $this->value = $_value29;
            }

            if ($_success) {
                $this->value = call_user_func(function () use (&$parameter, &$parameters) {
                    return $parameters;
                });
            }
        }

        $this->cut = $_cut31;

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

        $_position34 = $this->position;
        $_cut35 = $this->cut;

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
            $this->position = $_position34;

            $_value32 = array();

            if (substr($this->string, $this->position, strlen("(")) === "(") {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen("("));
                $this->position += strlen("(");
            } else {
                $_success = false;

                $this->report($this->position, '"("');
            }

            if ($_success) {
                $_value32[] = $this->value;

                $_success = $this->parse_();
            }

            if ($_success) {
                $_value32[] = $this->value;

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
                $_value32[] = $this->value;

                $this->value = $_value32;
            }

            if ($_success) {
                $this->value = call_user_func(function () use (&$argument) {
                    return [];
                });
            }
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position34;

            $_value33 = array();

            if (substr($this->string, $this->position, strlen("(")) === "(") {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen("("));
                $this->position += strlen("(");
            } else {
                $_success = false;

                $this->report($this->position, '"("');
            }

            if ($_success) {
                $_value33[] = $this->value;

                $_success = $this->parse_();
            }

            if ($_success) {
                $_value33[] = $this->value;

                $_success = $this->parseExpressionList();

                if ($_success) {
                    $arguments = $this->value;
                }
            }

            if ($_success) {
                $_value33[] = $this->value;

                $_success = $this->parse_();
            }

            if ($_success) {
                $_value33[] = $this->value;

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
                $_value33[] = $this->value;

                $this->value = $_value33;
            }

            if ($_success) {
                $this->value = call_user_func(function () use (&$argument, &$arguments) {
                    return $arguments;
                });
            }
        }

        $this->cut = $_cut35;

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

        $_value40 = array();

        $_success = $this->parseIdentifier();

        if ($_success) {
            $first = $this->value;
        }

        if ($_success) {
            $_value40[] = $this->value;

            $_value38 = array();
            $_cut39 = $this->cut;

            while (true) {
                $_position37 = $this->position;

                $this->cut = false;
                $_value36 = array();

                $_success = $this->parse_();

                if ($_success) {
                    $_value36[] = $this->value;

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
                    $_value36[] = $this->value;

                    $_success = $this->parse_();
                }

                if ($_success) {
                    $_value36[] = $this->value;

                    $_success = $this->parseIdentifier();

                    if ($_success) {
                        $next = $this->value;
                    }
                }

                if ($_success) {
                    $_value36[] = $this->value;

                    $this->value = $_value36;
                }

                if ($_success) {
                    $this->value = call_user_func(function () use (&$first, &$next) {
                        return $next;
                    });
                }

                if (!$_success) {
                    break;
                }

                $_value38[] = $this->value;
            }

            if (!$this->cut) {
                $_success = true;
                $this->position = $_position37;
                $this->value = $_value38;
            }

            $this->cut = $_cut39;

            if ($_success) {
                $rest = $this->value;
            }
        }

        if ($_success) {
            $_value40[] = $this->value;

            $this->value = $_value40;
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

        $_value45 = array();

        $_success = $this->parseExpression();

        if ($_success) {
            $first = $this->value;
        }

        if ($_success) {
            $_value45[] = $this->value;

            $_value43 = array();
            $_cut44 = $this->cut;

            while (true) {
                $_position42 = $this->position;

                $this->cut = false;
                $_value41 = array();

                $_success = $this->parse_();

                if ($_success) {
                    $_value41[] = $this->value;

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
                    $_value41[] = $this->value;

                    $_success = $this->parse_();
                }

                if ($_success) {
                    $_value41[] = $this->value;

                    $_success = $this->parseExpression();

                    if ($_success) {
                        $next = $this->value;
                    }
                }

                if ($_success) {
                    $_value41[] = $this->value;

                    $this->value = $_value41;
                }

                if ($_success) {
                    $this->value = call_user_func(function () use (&$first, &$next) {
                        return $next;
                    });
                }

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

            if ($_success) {
                $rest = $this->value;
            }
        }

        if ($_success) {
            $_value45[] = $this->value;

            $this->value = $_value45;
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