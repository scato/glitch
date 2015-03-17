<?php

namespace Glitch\Grammar;

use Glitch\Grammar\Tree\ActionNode;
use Glitch\Grammar\Tree\AddListenerNode;
use Glitch\Grammar\Tree\AssignmentNode;
use Glitch\Grammar\Tree\EventDefinitionNode;
use Glitch\Grammar\Tree\FireNode;
use Glitch\Grammar\Tree\ProgramNode;
use Glitch\Grammar\Tree\ReferenceNode;
use Glitch\Grammar\Tree\RemoveListenerNode;
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

    protected function parseStatement()
    {
        $_position = $this->position;

        if (isset($this->cache['Statement'][$_position])) {
            $_success = $this->cache['Statement'][$_position]['success'];
            $this->position = $this->cache['Statement'][$_position]['position'];
            $this->value = $this->cache['Statement'][$_position]['value'];

            return $_success;
        }

        $_position2 = $this->position;
        $_cut3 = $this->cut;

        $this->cut = false;
        $_success = $this->parseEventDefinitionStatement();

        if (!$_success && !$this->cut) {
            $this->position = $_position2;

            $_success = $this->parseAssignmentStatement();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position2;

            $_success = $this->parseFireStatement();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position2;

            $_success = $this->parseAddListenerStatement();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position2;

            $_success = $this->parseRemoveListenerStatement();
        }

        $this->cut = $_cut3;

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

    protected function parseEventDefinitionStatement()
    {
        $_position = $this->position;

        if (isset($this->cache['EventDefinitionStatement'][$_position])) {
            $_success = $this->cache['EventDefinitionStatement'][$_position]['success'];
            $this->position = $this->cache['EventDefinitionStatement'][$_position]['position'];
            $this->value = $this->cache['EventDefinitionStatement'][$_position]['value'];

            return $_success;
        }

        $_value4 = array();

        if (substr($this->string, $this->position, strlen("*")) === "*") {
            $_success = true;
            $this->value = substr($this->string, $this->position, strlen("*"));
            $this->position += strlen("*");
        } else {
            $_success = false;

            $this->report($this->position, '"*"');
        }

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

        $this->cache['EventDefinitionStatement'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'EventDefinitionStatement');
        }

        return $_success;
    }

    protected function parseAssignmentStatement()
    {
        $_position = $this->position;

        if (isset($this->cache['AssignmentStatement'][$_position])) {
            $_success = $this->cache['AssignmentStatement'][$_position]['success'];
            $this->position = $this->cache['AssignmentStatement'][$_position]['position'];
            $this->value = $this->cache['AssignmentStatement'][$_position]['value'];

            return $_success;
        }

        $_value5 = array();

        $_success = $this->parseExpression();

        if ($_success) {
            $left = $this->value;
        }

        if ($_success) {
            $_value5[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value5[] = $this->value;

            if (substr($this->string, $this->position, strlen(":=")) === ":=") {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen(":="));
                $this->position += strlen(":=");
            } else {
                $_success = false;

                $this->report($this->position, '":="');
            }
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
            $this->value = call_user_func(function () use (&$left, &$right) {
                return new AssignmentNode($left, $right);
            });
        }

        $this->cache['AssignmentStatement'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'AssignmentStatement');
        }

        return $_success;
    }

    protected function parseFireStatement()
    {
        $_position = $this->position;

        if (isset($this->cache['FireStatement'][$_position])) {
            $_success = $this->cache['FireStatement'][$_position]['success'];
            $this->position = $this->cache['FireStatement'][$_position]['position'];
            $this->value = $this->cache['FireStatement'][$_position]['value'];

            return $_success;
        }

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

            if (substr($this->string, $this->position, strlen("!")) === "!") {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen("!"));
                $this->position += strlen("!");
            } else {
                $_success = false;

                $this->report($this->position, '"!"');
            }
        }

        if ($_success) {
            $_value6[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value6[] = $this->value;

            $_success = $this->parseArgumentList();

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
            $this->value = call_user_func(function () use (&$left, &$right) {
                return new FireNode($left, $right);
            });
        }

        $this->cache['FireStatement'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'FireStatement');
        }

        return $_success;
    }

    protected function parseAddListenerStatement()
    {
        $_position = $this->position;

        if (isset($this->cache['AddListenerStatement'][$_position])) {
            $_success = $this->cache['AddListenerStatement'][$_position]['success'];
            $this->position = $this->cache['AddListenerStatement'][$_position]['position'];
            $this->value = $this->cache['AddListenerStatement'][$_position]['value'];

            return $_success;
        }

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

            if (substr($this->string, $this->position, strlen("+=")) === "+=") {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen("+="));
                $this->position += strlen("+=");
            } else {
                $_success = false;

                $this->report($this->position, '"+="');
            }
        }

        if ($_success) {
            $_value7[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value7[] = $this->value;

            $_success = $this->parseExpression();

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
            $this->value = call_user_func(function () use (&$left, &$right) {
                return new AddListenerNode($left, $right);
            });
        }

        $this->cache['AddListenerStatement'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'AddListenerStatement');
        }

        return $_success;
    }

    protected function parseRemoveListenerStatement()
    {
        $_position = $this->position;

        if (isset($this->cache['RemoveListenerStatement'][$_position])) {
            $_success = $this->cache['RemoveListenerStatement'][$_position]['success'];
            $this->position = $this->cache['RemoveListenerStatement'][$_position]['position'];
            $this->value = $this->cache['RemoveListenerStatement'][$_position]['value'];

            return $_success;
        }

        $_value8 = array();

        $_success = $this->parseExpression();

        if ($_success) {
            $left = $this->value;
        }

        if ($_success) {
            $_value8[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value8[] = $this->value;

            if (substr($this->string, $this->position, strlen("-=")) === "-=") {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen("-="));
                $this->position += strlen("-=");
            } else {
                $_success = false;

                $this->report($this->position, '"-="');
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
                $right = $this->value;
            }
        }

        if ($_success) {
            $_value8[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value8[] = $this->value;

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
            $_value8[] = $this->value;

            $this->value = $_value8;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$left, &$right) {
                return new RemoveListenerNode($left, $right);
            });
        }

        $this->cache['RemoveListenerStatement'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'RemoveListenerStatement');
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

        $_position9 = $this->position;
        $_cut10 = $this->cut;

        $this->cut = false;
        $_success = $this->parseActionLiteral();

        if (!$_success && !$this->cut) {
            $this->position = $_position9;

            $_success = $this->parseReferenceExpression();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position9;

            $_success = $this->parseStringLiteral();
        }

        $this->cut = $_cut10;

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

    protected function parseActionLiteral()
    {
        $_position = $this->position;

        if (isset($this->cache['ActionLiteral'][$_position])) {
            $_success = $this->cache['ActionLiteral'][$_position]['success'];
            $this->position = $this->cache['ActionLiteral'][$_position]['position'];
            $this->value = $this->cache['ActionLiteral'][$_position]['value'];

            return $_success;
        }

        $_value19 = array();

        $_success = $this->parseParameterList();

        if ($_success) {
            $parameters = $this->value;
        }

        if ($_success) {
            $_value19[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value19[] = $this->value;

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
            $_value19[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value19[] = $this->value;

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
            $_value19[] = $this->value;

            $_success = $this->parseStatementList();

            if ($_success) {
                $statements = $this->value;
            }
        }

        if ($_success) {
            $_value19[] = $this->value;

            $_success = $this->parse_();
        }

        if ($_success) {
            $_value19[] = $this->value;

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
            $_value19[] = $this->value;

            $this->value = $_value19;
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

    protected function parse_()
    {
        $_position = $this->position;

        if (isset($this->cache['_'][$_position])) {
            $_success = $this->cache['_'][$_position]['success'];
            $this->position = $this->cache['_'][$_position]['position'];
            $this->value = $this->cache['_'][$_position]['value'];

            return $_success;
        }

        $_value21 = array();
        $_cut22 = $this->cut;

        while (true) {
            $_position20 = $this->position;

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

            $_value21[] = $this->value;
        }

        if (!$this->cut) {
            $_success = true;
            $this->position = $_position20;
            $this->value = $_value21;
        }

        $this->cut = $_cut22;

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

        $_position27 = $this->position;

        $_value26 = array();

        if (preg_match('/^[A-Za-z_]$/', substr($this->string, $this->position, 1))) {
            $_success = true;
            $this->value = substr($this->string, $this->position, 1);
            $this->position += 1;
        } else {
            $_success = false;
        }

        if ($_success) {
            $_value26[] = $this->value;

            $_value24 = array();
            $_cut25 = $this->cut;

            while (true) {
                $_position23 = $this->position;

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
            $this->value = strval(substr($this->string, $_position27, $this->position - $_position27));
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

        $_value30 = array();
        $_cut31 = $this->cut;

        while (true) {
            $_position29 = $this->position;

            $this->cut = false;
            $_value28 = array();

            $_success = $this->parse_();

            if ($_success) {
                $_value28[] = $this->value;

                $_success = $this->parseStatement();

                if ($_success) {
                    $statement = $this->value;
                }
            }

            if ($_success) {
                $_value28[] = $this->value;

                $this->value = $_value28;
            }

            if ($_success) {
                $this->value = call_user_func(function () use (&$statement) {
                    return $statement;
                });
            }

            if (!$_success) {
                break;
            }

            $_value30[] = $this->value;
        }

        if (!$this->cut) {
            $_success = true;
            $this->position = $_position29;
            $this->value = $_value30;
        }

        $this->cut = $_cut31;

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

        $_success = $this->parseIdentifier();

        if ($_success) {
            $parameter = $this->value;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$parameter) {
                return [$parameter];
            });
        }

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

        $_position33 = $this->position;
        $_cut34 = $this->cut;

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
            $this->position = $_position33;

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

                $_success = $this->parseExpressionList();

                if ($_success) {
                    $arguments = $this->value;
                }
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
                $this->value = call_user_func(function () use (&$argument, &$arguments) {
                    return $arguments;
                });
            }
        }

        $this->cut = $_cut34;

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

        $_value39 = array();

        $_success = $this->parseIdentifier();

        if ($_success) {
            $first = $this->value;
        }

        if ($_success) {
            $_value39[] = $this->value;

            $_value37 = array();
            $_cut38 = $this->cut;

            while (true) {
                $_position36 = $this->position;

                $this->cut = false;
                $_value35 = array();

                $_success = $this->parse_();

                if ($_success) {
                    $_value35[] = $this->value;

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
                    $_value35[] = $this->value;

                    $_success = $this->parse_();
                }

                if ($_success) {
                    $_value35[] = $this->value;

                    $_success = $this->parseIdentifier();

                    if ($_success) {
                        $next = $this->value;
                    }
                }

                if ($_success) {
                    $_value35[] = $this->value;

                    $this->value = $_value35;
                }

                if ($_success) {
                    $this->value = call_user_func(function () use (&$first, &$next) {
                        return $next;
                    });
                }

                if (!$_success) {
                    break;
                }

                $_value37[] = $this->value;
            }

            if (!$this->cut) {
                $_success = true;
                $this->position = $_position36;
                $this->value = $_value37;
            }

            $this->cut = $_cut38;

            if ($_success) {
                $rest = $this->value;
            }
        }

        if ($_success) {
            $_value39[] = $this->value;

            $this->value = $_value39;
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

        $_value44 = array();

        $_success = $this->parseExpression();

        if ($_success) {
            $first = $this->value;
        }

        if ($_success) {
            $_value44[] = $this->value;

            $_value42 = array();
            $_cut43 = $this->cut;

            while (true) {
                $_position41 = $this->position;

                $this->cut = false;
                $_value40 = array();

                $_success = $this->parse_();

                if ($_success) {
                    $_value40[] = $this->value;

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
                    $_value40[] = $this->value;

                    $_success = $this->parse_();
                }

                if ($_success) {
                    $_value40[] = $this->value;

                    $_success = $this->parseExpression();

                    if ($_success) {
                        $next = $this->value;
                    }
                }

                if ($_success) {
                    $_value40[] = $this->value;

                    $this->value = $_value40;
                }

                if ($_success) {
                    $this->value = call_user_func(function () use (&$first, &$next) {
                        return $next;
                    });
                }

                if (!$_success) {
                    break;
                }

                $_value42[] = $this->value;
            }

            if (!$this->cut) {
                $_success = true;
                $this->position = $_position41;
                $this->value = $_value42;
            }

            $this->cut = $_cut43;

            if ($_success) {
                $rest = $this->value;
            }
        }

        if ($_success) {
            $_value44[] = $this->value;

            $this->value = $_value44;
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