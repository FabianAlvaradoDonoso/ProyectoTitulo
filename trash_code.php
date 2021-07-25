<?php
// --------------------------------------------------------------------------
foreach ($course_documents as $pivot ) {
    foreach ($documents as $document) {
        if($document->_id == $pivot->document){
            $pivot['document_name'] = $document->name;
            $pivot['document_size'] = $document->size;
            $pivot['document_type'] = $document->type;
        }
    }
    foreach ($courses as $curso ) {
        if($curso->_id == $pivot->course){
            $pivot['code_course'] = $curso->code;
        }
    }
    for ($i=0; $i < count($allowedMimes); $i++) {
        if($allowedMimes[$i]['id'] == $pivot->document_type){
            $pivot['type_id'] = $allowedMimes[$i]['type'];
        }
    }
}




// --------------------------------------------------------------------------
$key=array_search("21041", array_column(json_decode(json_encode($careers),TRUE), 'code'));


// --------------------------------------------------------------------------
$clave = Key::createNewRandomKey();;
