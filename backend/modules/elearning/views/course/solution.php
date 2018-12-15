<?php
function solution($S) {
    if (strlen($S) == 0) // Break point 1: S is empty -> true
         return 1;
 
     $brackets = new BracketList();
     $len = strlen($S);
     
     for ($c = 0; $c < $len; $c++) {
         $char = $S[$c];
          if ($char == '<' || $char == '{' || $char == '(' || $char == '[') { // If char is open -> push char to top of stack array
             $brackets->push($char);
         } else {
             if ($brackets->stackSize() == 0){ // Break point 2: If char is close but stack array is emplty -> false
                 return 0;
             }
 
             if ($char === ')') {
                 if ($brackets->top() === '('){
                     // If char is ) and top of stach is ( => Remove ( from stack array
                     $brackets->pop();
                 }
                 else{ 
                     // Break point 3: nested is false
                     return 0;
                 }
             }
 
             if ($char === '}') {
                 if ($brackets->top() === '{'){
                      // Same )    
                     $brackets->pop();
                 }
                 else{  
                     return 0;
                 }
             }
 
             if ($char === '>') {
                 if ($brackets->top() === '<'){
                     // Same )
                     $brackets->pop();
                 }
                 else{          
                     return 0;
                 }
             }
             if ($char === ']') {
                if ($brackets->top() === '['){
                    // Same )
                    $brackets->pop();
                }
                else{          
                    return 0;
                }
            }
         }
     }
     
  
     if ($brackets->stackSize() == 0)
        // Break point 4: After loop, all char is removed from stack -> True
         return 1;
        // Break point 5: else -> false
     return 0;
 }
 
 class BracketList {
 
     protected $stack; 
     public function __construct() {
         $this->stack = array(); 
     }
     public function stackSize() {
         return sizeof($this->stack);
     }
     public function push($data) {
         array_unshift($this->stack, $data);
     }
     public function pop() {
         if (!empty($this->stack)) {
             array_shift($this->stack);
         }
     }
     public function isEmpty() {
         return empty($this->stack);
     }
     public function top() {
         if (!empty($this->stack))
             return $this->stack[0];
     }
 }


 