function resetPersona() {
    $.post("/i-miei-corsi/script/resetCorsiStudente.php", function(result) {
        result = result.trim();

        if(result === "reset-effettuato") {
            $.confirm({
                title: "Operazione completata",
                content: "Reset effettuato! Clicca <code>Iscriviti</code> per iscriverti di nuovo. Clicca Esci se non intendi iscriverti di nuovo.",
                escapeKey: true,
                backgroundDismiss: true,
                theme: "modern",
                buttons: {
                    confirm: {
                        text: 'Iscriviti',
                        keys: ['enter'],
                        action: function() {
                            const url = location.protocol + "//" + location.hostname + "/iscrizione/";
                            window.location = url; //reindirizzamento a Iscrizione
                        }
                    },
                    cancel: {
                        text: 'Esci',
                        action: function() {
                            const page_url = location.href;
                            window.location = page_url; //equivalente a F5 (ricarica la pagina)
                        }
                    }
                }
            });
        } else {
            $alert(
                "Operazione non completata",
                "Non è stato possibile effettuare il reset. Se riprovando il problema persiste, contattaci."
            );
        }
    });
}

function stampaCorsi() {
    $("button#btnPrint").click(function() {
        if($("div#no_iscrizione").length) {
            $alert(
                "Operazione non completata",
                "Non ti sei ancora iscritto ad alcun corso!"
            );
        } else window.print();
    });
}

$(document).ready(function() {
    //gestore per la funzionalità per mandare in stampa la lista dei corsi a cui si è iscritto lo studente
    stampaCorsi();

    $("button#btnReset").click(function() {
        if($("div#no_iscrizione").length) {
            $alert(
                "Operazione non completata",
                "Non ti sei ancora iscritto ad alcun corso!"
            );
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
});
