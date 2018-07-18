function resetPersona() {
    $.post("/i-miei-corsi/script/resetPersona.php",function(result) {
        if(result.trim()=="reset-effettuato") {
            let titolo="Operazione completata",contenuto="Reset effettuato! Verrai reindirizzato alla pagina per effettuare nuovamente l'iscrizione.";
            $alert(titolo,contenuto);
            const url=location.protocol+"//"+location.hostname+"/iscrizione/";
            window.location = url; //reindirizzamento a Iscrizione
        } else {
            let titolo="Operazione non completata",contenuto="Non è stato possibile effettuare il reset. Se riprovando il problema persiste, contattaci.";
            $alert(titolo,contenuto);
        }
    });
}
function stampaCorsi() {
    $("button#btnPrint").click(function() {
        if($("div#no_iscrizione").length) {
            let titolo="Operazione non completata",contenuto="Non ti sei ancora iscritto ad alcun corso!";
            $alert(titolo,contenuto);
        } else {
            window.print();
        }
    });
}
$(document).ready(function() {
    stampaCorsi();
    $("button#btnReset").click(function() {
        if($("div#no_iscrizione").length) {
            let titolo="Operazione non completata",contenuto="Non ti sei ancora iscritto ad alcun corso!";
            $alert(titolo,contenuto);
        } else {
            $.confirm({
                escapeKey: true,
                backgroundDismiss: true,
                theme: "modern",
                title: "Richiesta disiscrizione",
                content: "Vuoi veramente disiscriverti da tutti i corsi che hai già scelto di frequentare? Dovrai effettuare nuovamente l'iscrizione. L'operazione è irreversibile. Premere Ok per continuare.",
                buttons: {
                    confirm: {
                        text: "Ok",
                        btnClass: "btn-success",
                        keys: ['enter'],
                        action: function() {
                            resetPersona();
                        }
                    },
                    cancel: {
                        text: "Annulla",
                        btnClass: "btn-danger"
                    }
                }
            });
        }
    });
})
