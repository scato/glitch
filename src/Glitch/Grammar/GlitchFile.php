<?php

namespace Glitch\Grammar;

use Glitch\Grammar\Tree\ActionNode;
use Glitch\Grammar\Tree\AssignmentNode;
use Glitch\Grammar\Tree\CallNode;
use Glitch\Grammar\Tree\EventDefinitionNode;
use Glitch\Grammar\Tree\EventListenerNode;
use Glitch\Grammar\Tree\FireNode;
use Glitch\Grammar\Tree\FunctionNode;
use Glitch\Grammar\Tree\ProgramNode;
use Glitch\Grammar\Tree\ReferenceNode;
use Glitch\Grammar\Tree\StringNode;

class GlitchFile
{
    protected $string;
    protected $position;
    protected $value;
    protected $cache;
    protected $cut;
    protected $errors;
    protected $warnings;

    protected function parseProgram()
    {
        $_position = $this->position;

        if (isset($this->cache['Program'][$_position])) {
            $_success = $this->cache['Program'][$_position]['success'];
            $this->position = $this->cache['Program'][$_position]['position'];
            $this->value = $this->cache['Program'][$_position]['value'];

            return $_success;
        }

        $_value1 = array();

        $_success = $this->parseStatementList();

        if ($_success) {
            $statements = $this->value;
        }

        if ($_success) {
            $_value1[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value1[] = $this->value;

            $this->value = $_value1;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$statements) {
                return new ProgramNode($statements);
            });
        }

        $this->cache['Program'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'Program');
        }

        return $_success;
    }

    protected function parseDEF_EV()
    {
        $_position = $this->position;

        if (isset($this->cache['DEF_EV'][$_position])) {
            $_success = $this->cache['DEF_EV'][$_position]['success'];
            $this->position = $this->cache['DEF_EV'][$_position]['position'];
            $this->value = $this->cache['DEF_EV'][$_position]['value'];

            return $_success;
        }

        if (substr($this->string, $this->position, strlen("*")) === "*") {
            $_success = true;
            $this->value = substr($this->string, $this->position, strlen("*"));
            $this->position += strlen("*");
        } else {
            $_success = false;

            $this->report($this->position, '"*"');
        }

        $this->cache['DEF_EV'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'DEF_EV');
        }

        return $_success;
    }

    protected function parseASSIGN()
    {
        $_position = $this->position;

        if (isset($this->cache['ASSIGN'][$_position])) {
            $_success = $this->cache['ASSIGN'][$_position]['success'];
            $this->position = $this->cache['ASSIGN'][$_position]['position'];
            $this->value = $this->cache['ASSIGN'][$_position]['value'];

            return $_success;
        }

        if (substr($this->string, $this->position, strlen(":=")) === ":=") {
            $_success = true;
            $this->value = substr($this->string, $this->position, strlen(":="));
            $this->position += strlen(":=");
        } else {
            $_success = false;

            $this->report($this->position, '":="');
        }

        $this->cache['ASSIGN'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'ASSIGN');
        }

        return $_success;
    }

    protected function parseEV_OP()
    {
        $_position = $this->position;

        if (isset($this->cache['EV_OP'][$_position])) {
            $_success = $this->cache['EV_OP'][$_position]['success'];
            $this->position = $this->cache['EV_OP'][$_position]['position'];
            $this->value = $this->cache['EV_OP'][$_position]['value'];

            return $_success;
        }

        $_position2 = $this->position;
        $_cut3 = $this->cut;

        $this->cut = false;
        if (substr($this->string, $this->position, strlen("+=")) === "+=") {
            $_success = true;
            $this->value = substr($this->string, $this->position, strlen("+="));
            $this->position += strlen("+=");
        } else {
            $_success = false;

            $this->report($this->position, '"+="');
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position2;

            if (substr($this->string, $this->position, strlen("-=")) === "-=") {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen("-="));
                $this->position += strlen("-=");
            } else {
                $_success = false;

                $this->report($this->position, '"-="');
            }
        }

        $this->cut = $_cut3;

        $this->cache['EV_OP'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'EV_OP');
        }

        return $_success;
    }

    protected function parseFIRE()
    {
        $_position = $this->position;

        if (isset($this->cache['FIRE'][$_position])) {
            $_success = $this->cache['FIRE'][$_position]['success'];
            $this->position = $this->cache['FIRE'][$_position]['position'];
            $this->value = $this->cache['FIRE'][$_position]['value'];

            return $_success;
        }

        if (substr($this->string, $this->position, strlen("!")) === "!") {
            $_success = true;
            $this->value = substr($this->string, $this->position, strlen("!"));
            $this->position += strlen("!");
        } else {
            $_success = false;

            $this->report($this->position, '"!"');
        }

        $this->cache['FIRE'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'FIRE');
        }

        return $_success;
    }

    protected function parseStatement()
    {
        $_position = $this->position;

        if (isset($this->cache['Statement'][$_position])) {
            $_success = $this->cache['Statement'][$_position]['success'];
            $this->position = $this->cache['Statement'][$_position]['position'];
            $this->value = $this->cache['Statement'][$_position]['value'];

            return $_success;
        }

        $_position8 = $this->position;
        $_cut9 = $this->cut;

        $this->cut = false;
        $_value4 = array();

        $_success = $this->parseDEF_EV();

        if ($_success) {
            $_value4[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value4[] = $this->value;

            $_success = $this->parseIdentifierList();

            if ($_success) {
                $names = $this->value;
            }
        }

        if ($_success) {
            $_value4[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value4[] = $this->value;

            if (substr($this->string, $this->position, strlen(";")) === ";") {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen(";"));
                $this->position += strlen(";");
            } else {
                $_success = false;

                $this->report($this->position, '";"');
            }
        }

        if ($_success) {
            $_value4[] = $this->value;

            $this->value = $_value4;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$names) {
                return new EventDefinitionNode($names);
            });
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position8;

            $_value5 = array();

            $_success = $this->parseIdentifier();

            if ($_success) {
                $left = $this->value;
            }

            if ($_success) {
                $_value5[] = $this->value;

                $_success = $this->parse_();
            }

            if ($_success) {
                $_value5[] = $this->value;

                $_success = $this->parseASSIGN();
            }

            if ($_success) {
                $_value5[] = $this->value;

                $_success = $this->parse_();
            }

            if ($_success) {
                $_value5[] = $this->value;

                $_success = $this->parseExpression();

                if ($_success) {
                    $right = $this->value;
                }
            }

            if ($_success) {
                $_value5[] = $this->value;

                $_success = $this->parse_();
            }

            if ($_success) {
                $_value5[] = $this->value;

                if (substr($this->string, $this->position, strlen(";")) === ";") {
                    $_success = true;
                    $this->value = substr($this->string, $this->position, strlen(";"));
                    $this->position += strlen(";");
                } else {
                    $_success = false;

                    $this->report($this->position, '";"');
                }
            }

            if ($_success) {
                $_value5[] = $this->value;

                $this->value = $_value5;
            }

            if ($_success) {
                $this->value = call_user_func(function () use (&$names, &$left, &$right) {
                    return new AssignmentNode($left, $right);
                });
            }
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position8;

            $_value6 = array();

            $_success = $this->parseExpression();

            if ($_success) {
                $left = $this->value;
            }

            if ($_success) {
                $_value6[] = $this->value;

                $_success = $this->parse_();
            }

            if ($_success) {
                $_value6[] = $this->value;

                $_success = $this->parseEV_OP();

                if ($_success) {
                    $operator = $this->value;
                }
            }

            if ($_success) {
                $_value6[] = $this->value;

                $_success = $this->parse_();
            }

            if ($_success) {
                $_value6[] = $this->value;

                $_success = $this->parseExpression();

                if ($_success) {
                    $right = $this->value;
                }
            }

            if ($_success) {
                $_value6[] = $this->value;

                $_success = $this->parse_();
            }

            if ($_success) {
                $_value6[] = $this->value;

                if (substr($this->string, $this->position, strlen(";")) === ";") {
                    $_success = true;
                    $this->value = substr($this->string, $this->position, strlen(";"));
                    $this->position += strlen(";");
                } else {
                    $_success = false;

                    $this->report($this->position, '";"');
                }
            }

            if ($_success) {
                $_value6[] = $this->value;

                $this->value = $_value6;
            }

            if ($_success) {
                $this->value = call_user_func(function () use (&$names, &$left, &$right, &$left, &$operator, &$right) {
                    return new EventListenerNode($left, $operator, $right);
                });
            }
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position8;

            $_value7 = array();

            $_success = $this->parseExpression();

            if ($_success) {
                $left = $this->value;
            }

            if ($_success) {
                $_value7[] = $this->value;

                $_success = $this->parse_();
            }

            if ($_success) {
                $_value7[] = $this->value;

                $_success = $this->parseFIRE();
            }

            if ($_success) {
                $_value7[] = $this->value;

                $_success = $this->parse_();
            }

            if ($_success) {
                $_value7[] = $this->value;

                $_success = $this->parseArgumentList();

                if ($_success) {
                    $right = $this->value;
                }
            }

            if ($_success) {
                $_value7[] = $this->value;

                $_success = $this->parse_();
            }

            if ($_success) {
                $_value7[] = $this->value;

                if (substr($this->string, $this->position, strlen(";")) === ";") {
                    $_success = true;
                    $this->value = substr($this->string, $this->position, strlen(";"));
                    $this->position += strlen(";");
                } else {
                    $_success = false;

                    $this->report($this->position, '";"');
                }
            }

            if ($_success) {
                $_value7[] = $this->value;

                $this->value = $_value7;
            }

            if ($_success) {
                $this->value = call_user_func(function () use (&$names, &$left, &$right, &$left, &$operator, &$right, &$left, &$right) {
                    return new FireNode($left, $right);
                });
            }
        }

        $this->cut = $_cut9;

        $this->cache['Statement'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'Statement');
        }

        return $_success;
    }

    protected function parseExpression()
    {
        $_position = $this->position;

        if (isset($this->cache['Expression'][$_position])) {
            $_success = $this->cache['Expression'][$_position]['success'];
            $this->position = $this->cache['Expression'][$_position]['position'];
            $this->value = $this->cache['Expression'][$_position]['value'];

            return $_success;
        }

        $_position10 = $this->position;
        $_cut11 = $this->cut;

        $this->cut = false;
        $_success = $this->parseActionLiteral();

        if (!$_success && !$this->cut) {
            $this->position = $_position10;

            $_success = $this->parseFunctionLiteral();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position10;

            $_success = $this->parseCallExpression();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position10;

            $_success = $this->parsePrimaryExpression();
        }

        $this->cut = $_cut11;

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

    protected function parsePrimaryExpression()
    {
        $_position = $this->position;

        if (isset($this->cache['PrimaryExpression'][$_position])) {
            $_success = $this->cache['PrimaryExpression'][$_position]['success'];
            $this->position = $this->cache['PrimaryExpression'][$_position]['position'];
            $this->value = $this->cache['PrimaryExpression'][$_position]['value'];

            return $_success;
        }

        $_position12 = $this->position;
        $_cut13 = $this->cut;

        $this->cut = false;
        $_success = $this->parseStringLiteral();

        if (!$_success && !$this->cut) {
            $this->position = $_position12;

            $_success = $this->parseReferenceExpression();
        }

        $this->cut = $_cut13;

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

    protected function parseReferenceExpression()
    {
        $_position = $this->position;

        if (isset($this->cache['ReferenceExpression'][$_position])) {
            $_success = $this->cache['ReferenceExpression'][$_position]['success'];
            $this->position = $this->cache['ReferenceExpression'][$_position]['position'];
            $this->value = $this->cache['ReferenceExpression'][$_position]['value'];

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

        $this->cache['ReferenceExpression'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'ReferenceExpression');
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

        $_position16 = $this->position;
        $_cut17 = $this->cut;

        $this->cut = false;
        $_value14 = array();

        $_success = $this->parsePrimaryExpression();

        if ($_success) {
            $left = $this->value;
        }

        if ($_success) {
            $_value14[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value14[] = $this->value;

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
            $_value14[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value14[] = $this->value;

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
            $_value14[] = $this->value;

            $this->value = $_value14;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$left) {
                return new CallNode($left, []);
            });
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position16;

            $_value15 = array();

            $_success = $this->parsePrimaryExpression();

            if ($_success) {
                $left = $this->value;
            }

            if ($_success) {
                $_value15[] = $this->value;

                $_success = $this->parse_();
            }

            if ($_success) {
                $_value15[] = $this->value;

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
                $_value15[] = $this->value;

                $_success = $this->parse_();
            }

            if ($_success) {
                $_value15[] = $this->value;

                $_success = $this->parseExpressionList();

                if ($_success) {
                    $right = $this->value;
                }
            }

            if ($_success) {
                $_value15[] = $this->value;

                $_success = $this->parse_();
            }

            if ($_success) {
                $_value15[] = $this->value;

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
                $_value15[] = $this->value;

                $this->value = $_value15;
            }

            if ($_success) {
                $this->value = call_user_func(function () use (&$left, &$left, &$right) {
                    return new CallNode($left, $right);
                });
            }
        }

        $this->cut = $_cut17;

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

    protected function parseStringLiteral()
    {
        $_position = $this->position;

        if (isset($this->cache['StringLiteral'][$_position])) {
            $_success = $this->cache['StringLiteral'][$_position]['success'];
            $this->position = $this->cache['StringLiteral'][$_position]['position'];
            $this->value = $this->cache['StringLiteral'][$_position]['value'];

            return $_success;
        }

        $_position25 = $this->position;

        $_value24 = array();

        if (substr($this->string, $this->position, strlen("\"")) === "\"") {
            $_success = true;
            $this->value = substr($this->string, $this->position, strlen("\""));
            $this->position += strlen("\"");
        } else {
            $_success = false;

            $this->report($this->position, '"\\""');
        }

        if ($_success) {
            $_value24[] = $this->value;

            $_value22 = array();
            $_cut23 = $this->cut;

            while (true) {
                $_position21 = $this->position;

                $this->cut = false;
                $_position19 = $this->position;
                $_cut20 = $this->cut;

                $this->cut = false;
                if (preg_match('/^[^\\\\"]$/', substr($this->string, $this->position, 1))) {
                    $_success = true;
                    $this->value = substr($this->string, $this->position, 1);
                    $this->position += 1;
                } else {
                    $_success = false;
                }

                if (!$_success && !$this->cut) {
                    $this->position = $_position19;

                    $_value18 = array();

                    if (substr($this->string, $this->position, strlen("\\")) === "\\") {
                        $_success = true;
                        $this->value = substr($this->string, $this->position, strlen("\\"));
                        $this->position += strlen("\\");
                    } else {
                        $_success = false;

                        $this->report($this->position, '"\\\\"');
                    }

                    if ($_success) {
                        $_value18[] = $this->value;

                        if ($this->position < strlen($this->string)) {
                            $_success = true;
                            $this->value = substr($this->string, $this->position, 1);
                            $this->position += 1;
                        } else {
                            $_success = false;
                        }
                    }

                    if ($_success) {
                        $_value18[] = $this->value;

                        $this->value = $_value18;
                    }
                }

                $this->cut = $_cut20;

                if (!$_success) {
                    break;
                }

                $_value22[] = $this->value;
            }

            if (!$this->cut) {
                $_success = true;
                $this->position = $_position21;
                $this->value = $_value22;
            }

            $this->cut = $_cut23;
        }

        if ($_success) {
            $_value24[] = $this->value;

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
            $_value24[] = $this->value;

            $this->value = $_value24;
        }

        if ($_success) {
            $this->value = strval(substr($this->string, $_position25, $this->position - $_position25));
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

    protected function parseActionLiteral()
    {
        $_position = $this->position;

        if (isset($this->cache['ActionLiteral'][$_position])) {
            $_success = $this->cache['ActionLiteral'][$_position]['success'];
            $this->position = $this->cache['ActionLiteral'][$_position]['position'];
            $this->value = $this->cache['ActionLiteral'][$_position]['value'];

            return $_success;
        }

        $_value26 = array();

        $_success = $this->parseParameterList();

        if ($_success) {
            $parameters = $this->value;
        }

        if ($_success) {
            $_value26[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value26[] = $this->value;

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
            $_value26[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value26[] = $this->value;

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
            $_value26[] = $this->value;

            $_success = $this->parseStatementList();

            if ($_success) {
                $statements = $this->value;
            }
        }

        if ($_success) {
            $_value26[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value26[] = $this->value;

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
            $_value26[] = $this->value;

            $this->value = $_value26;
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

        $_value27 = array();

        $_success = $this->parseParameterList();

        if ($_success) {
            $parameters = $this->value;
        }

        if ($_success) {
            $_value27[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value27[] = $this->value;

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
            $_value27[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value27[] = $this->value;

            $_success = $this->parseExpression();

            if ($_success) {
                $expression = $this->value;
            }
        }

        if ($_success) {
            $_value27[] = $this->value;

            $this->value = $_value27;
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

    protected function parse_()
    {
        $_position = $this->position;

        if (isset($this->cache['_'][$_position])) {
            $_success = $this->cache['_'][$_position]['success'];
            $this->position = $this->cache['_'][$_position]['position'];
            $this->value = $this->cache['_'][$_position]['value'];

            return $_success;
        }

        $_value29 = array();
        $_cut30 = $this->cut;

        while (true) {
            $_position28 = $this->position;

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

            $_value29[] = $this->value;
        }

        if (!$this->cut) {
            $_success = true;
            $this->position = $_position28;
            $this->value = $_value29;
        }

        $this->cut = $_cut30;

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

        $_position35 = $this->position;

        $_value34 = array();

        if (preg_match('/^[A-Za-z_]$/', substr($this->string, $this->position, 1))) {
            $_success = true;
            $this->value = substr($this->string, $this->position, 1);
            $this->position += 1;
        } else {
            $_success = false;
        }

        if ($_success) {
            $_value34[] = $this->value;

            $_value32 = array();
            $_cut33 = $this->cut;

            while (true) {
                $_position31 = $this->position;

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
            $this->value = strval(substr($this->string, $_position35, $this->position - $_position35));
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

        $_value38 = array();
        $_cut39 = $this->cut;

        while (true) {
            $_position37 = $this->position;

            $this->cut = false;
            $_value36 = array();

            $_success = $this->parse_();

            if ($_success) {
                $_value36[] = $this->value;

                $_success = $this->parseStatement();

                if ($_success) {
                    $statement = $this->value;
                }
            }

            if ($_success) {
                $_value36[] = $this->value;

                $this->value = $_value36;
            }

            if ($_success) {
                $this->value = call_user_func(function () use (&$statement) {
                    return $statement;
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

        $_position42 = $this->position;
        $_cut43 = $this->cut;

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
            $this->position = $_position42;

            $_value40 = array();

            if (substr($this->string, $this->position, strlen("(")) === "(") {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen("("));
                $this->position += strlen("(");
            } else {
                $_success = false;

                $this->report($this->position, '"("');
            }

            if ($_success) {
                $_value40[] = $this->value;

                $_success = $this->parse_();
            }

            if ($_success) {
                $_value40[] = $this->value;

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
                $_value40[] = $this->value;

                $this->value = $_value40;
            }

            if ($_success) {
                $this->value = call_user_func(function () use (&$parameter) {
                    return [];
                });
            }
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position42;

            $_value41 = array();

            if (substr($this->string, $this->position, strlen("(")) === "(") {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen("("));
                $this->position += strlen("(");
            } else {
                $_success = false;

                $this->report($this->position, '"("');
            }

            if ($_success) {
                $_value41[] = $this->value;

                $_success = $this->parse_();
            }

            if ($_success) {
                $_value41[] = $this->value;

                $_success = $this->parseIdentifierList();

                if ($_success) {
                    $parameters = $this->value;
                }
            }

            if ($_success) {
                $_value41[] = $this->value;

                $_success = $this->parse_();
            }

            if ($_success) {
                $_value41[] = $this->value;

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
                $_value41[] = $this->value;

                $this->value = $_value41;
            }

            if ($_success) {
                $this->value = call_user_func(function () use (&$parameter, &$parameters) {
                    return $parameters;
                });
            }
        }

        $this->cut = $_cut43;

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

        $_position46 = $this->position;
        $_cut47 = $this->cut;

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
            $this->position = $_position46;

            $_value44 = array();

            if (substr($this->string, $this->position, strlen("(")) === "(") {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen("("));
                $this->position += strlen("(");
            } else {
                $_success = false;

                $this->report($this->position, '"("');
            }

            if ($_success) {
                $_value44[] = $this->value;

                $_success = $this->parse_();
            }

            if ($_success) {
                $_value44[] = $this->value;

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
                $_value44[] = $this->value;

                $this->value = $_value44;
            }

            if ($_success) {
                $this->value = call_user_func(function () use (&$argument) {
                    return [];
                });
            }
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position46;

            $_value45 = array();

            if (substr($this->string, $this->position, strlen("(")) === "(") {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen("("));
                $this->position += strlen("(");
            } else {
                $_success = false;

                $this->report($this->position, '"("');
            }

            if ($_success) {
                $_value45[] = $this->value;

                $_success = $this->parse_();
            }

            if ($_success) {
                $_value45[] = $this->value;

                $_success = $this->parseExpressionList();

                if ($_success) {
                    $arguments = $this->value;
                }
            }

            if ($_success) {
                $_value45[] = $this->value;

                $_success = $this->parse_();
            }

            if ($_success) {
                $_value45[] = $this->value;

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
                $_value45[] = $this->value;

                $this->value = $_value45;
            }

            if ($_success) {
                $this->value = call_user_func(function () use (&$argument, &$arguments) {
                    return $arguments;
                });
            }
        }

        $this->cut = $_cut47;

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

        $_value52 = array();

        $_success = $this->parseIdentifier();

        if ($_success) {
            $first = $this->value;
        }

        if ($_success) {
            $_value52[] = $this->value;

            $_value50 = array();
            $_cut51 = $this->cut;

            while (true) {
                $_position49 = $this->position;

                $this->cut = false;
                $_value48 = array();

                $_success = $this->parse_();

                if ($_success) {
                    $_value48[] = $this->value;

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
                    $_value48[] = $this->value;

                    $_success = $this->parse_();
                }

                if ($_success) {
                    $_value48[] = $this->value;

                    $_success = $this->parseIdentifier();

                    if ($_success) {
                        $next = $this->value;
                    }
                }

                if ($_success) {
                    $_value48[] = $this->value;

                    $this->value = $_value48;
                }

                if ($_success) {
                    $this->value = call_user_func(function () use (&$first, &$next) {
                        return $next;
                    });
                }

                if (!$_success) {
                    break;
                }

                $_value50[] = $this->value;
            }

            if (!$this->cut) {
                $_success = true;
                $this->position = $_position49;
                $this->value = $_value50;
            }

            $this->cut = $_cut51;

            if ($_success) {
                $rest = $this->value;
            }
        }

        if ($_success) {
            $_value52[] = $this->value;

            $this->value = $_value52;
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

        $_value57 = array();

        $_success = $this->parseExpression();

        if ($_success) {
            $first = $this->value;
        }

        if ($_success) {
            $_value57[] = $this->value;

            $_value55 = array();
            $_cut56 = $this->cut;

            while (true) {
                $_position54 = $this->position;

                $this->cut = false;
                $_value53 = array();

                $_success = $this->parse_();

                if ($_success) {
                    $_value53[] = $this->value;

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
                    $_value53[] = $this->value;

                    $_success = $this->parse_();
                }

                if ($_success) {
                    $_value53[] = $this->value;

                    $_success = $this->parseExpression();

                    if ($_success) {
                        $next = $this->value;
                    }
                }

                if ($_success) {
                    $_value53[] = $this->value;

                    $this->value = $_value53;
                }

                if ($_success) {
                    $this->value = call_user_func(function () use (&$first, &$next) {
                        return $next;
                    });
                }

                if (!$_success) {
                    break;
                }

                $_value55[] = $this->value;
            }

            if (!$this->cut) {
                $_success = true;
                $this->position = $_position54;
                $this->value = $_value55;
            }

            $this->cut = $_cut56;

            if ($_success) {
                $rest = $this->value;
            }
        }

        if ($_success) {
            $_value57[] = $this->value;

            $this->value = $_value57;
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

        $_success = $this->parseProgram();

        if (!$_success) {
            throw new \InvalidArgumentException("Syntax error, expecting {$this->expecting()} on line {$this->line()}");
        }

        if ($this->position < strlen($this->string)) {
            throw new \InvalidArgumentException("Syntax error, unexpected {$this->rest()} on line {$this->line()}");
        }

        return $this->value;
    }
}