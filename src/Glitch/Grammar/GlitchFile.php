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

class GlitchFile extends GlitchExpression
{
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

    protected function parseStatementList()
    {
        $_position = $this->position;

        if (isset($this->cache['StatementList'][$_position])) {
            $_success = $this->cache['StatementList'][$_position]['success'];
            $this->position = $this->cache['StatementList'][$_position]['position'];
            $this->value = $this->cache['StatementList'][$_position]['value'];

            return $_success;
        }

        $_value12 = array();
        $_cut13 = $this->cut;

        while (true) {
            $_position11 = $this->position;

            $this->cut = false;
            $_value10 = array();

            $_success = $this->parse_();

            if ($_success) {
                $_value10[] = $this->value;

                $_success = $this->parseStatement();

                if ($_success) {
                    $statement = $this->value;
                }
            }

            if ($_success) {
                $_value10[] = $this->value;

                $this->value = $_value10;
            }

            if ($_success) {
                $this->value = call_user_func(function () use (&$statement) {
                    return $statement;
                });
            }

            if (!$_success) {
                break;
            }

            $_value12[] = $this->value;
        }

        if (!$this->cut) {
            $_success = true;
            $this->position = $_position11;
            $this->value = $_value12;
        }

        $this->cut = $_cut13;

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