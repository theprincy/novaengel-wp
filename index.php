<?php
/*
Plugin Name: Import Novaengel
Description: Import product and price from Novaengel

*/

// Assicurati che l'utente abbia i permessi necessari per accedere alla pagina di amministrazione
if ( ! current_user_can( 'manage_options' ) ) {
    wp_die( 'Non hai i permessi necessari per accedere a questa pagina.' );
}

// Variabile per controllare se l'operazione è stata interrotta
$stop_operation = false;

// Verifica se il form è stato inviato
if ( isset( $_POST['start_stop'] ) ) {
    // Verifica che il valore inviato sia valido
    if ( $_POST['start_stop'] === 'start' ) {
        $stop_operation = false;
        // Verifica che il file script.php esista
        if ( file_exists( plugin_dir_path( __FILE__ ) . 'script.php' ) ) {
            // Includi il file script.php
            include plugin_dir_path( __FILE__ ) . 'script.php';
            echo '<div id="message" class="updated">Operazione avviata con successo.</div>';
        } else {
            echo '<div id="message" class="error">Il file script.php non esiste.</div>';
        }
    } elseif ( $_POST['start_stop'] === 'stop' ) {
        $stop_operation = true;
        echo '<div id="message" class="updated">Operazione interrotta.</div>';
    } else {
        echo '<div id="message" class="error">Valore inviato non valido.</div>';
    }
}
// Includi il file CSS personalizzato
function execute_script_css() {
    wp_enqueue_style( 'execute-script-css', plugin_dir_url( __FILE__ ) . 'style.css' );
}
add_action( 'admin_head', 'execute_script_css' );

// eseguire un controllo periodico sullo stato dell'operazione.
function check_operation_status() {
    // Utilizzare una chiamata AJAX per verificare lo stato dell'operazione
    // e mostrare un messaggio all'utente
    if ( $stop_operation ) {
        echo '<div id="operation-status">Operazione interrotta.</div>';
    } else {
        echo '<div id="operation-status">Operazione in corso...</div>';
    }
}

function show_script_output() {
    if ( isset( $_POST['start_stop'] ) && $_POST['start_stop'] === 'start' ) {
        // Mostra l'output dello script qui
        echo '<div id="script-output">' . $output_from_script . '</div>';
    }
}
// Crea la pagina di amministrazione per il plugin
function execute_script_page() {
    add_menu_page( 'Esegui Script', 'Esegui Script', 'manage_options', 'execute_script', 'execute_script_content', '', 6 );
}
add_action( 'admin_menu', 'execute_script_page' );

// Crea il contenuto della pagina di amministrazione
function execute_script_content() {
    global $stop_operation;
    if ( isset( $_POST['start_stop'] ) && $_POST['start_stop'] === 'start' ) {
        // Esegui lo script
        $output_from_script = execute_script();

        // Imposta la variabile per la verifica dello stato dell'operazione
        $stop_operation = false;

        // Mostra l'output dello script
        echo '<div id="script-output">' . $output_from_script . '</div>';
    } elseif ( isset( $_POST['start_stop'] ) && $_POST['start_stop'] === 'stop' ) {
        // Interrompi l'operazione
        $stop_operation = true;
    }
    ?>
    <div class="wrap">
        <h1>Esegui Script</h1>
        <form method="post">
            <input type="submit" name="start_stop" value="start" class="button button-primary">Avvia</input>
            <input type="submit" name="start_stop" value="stop" class="button button-secondary">Interrompi</input>
        </form>
        <?php check_operation_status(); ?>
    </div>
    <?php
}

// Mostra l'output dello script
function show_script_output() {
    if ( isset( $_POST['start_stop'] ) && $_POST['start_stop'] === 'start' ) {
        // Mostra l'output dello script qui
        echo '<div id="script-output">' . $output_from_script . '</div>';
    }
}

// Verifica lo stato dell'operazione
function check_operation_status() {
    global $stop_operation;
// Utilizzare una variabile globale per verificare se l'operazione è stata interrotta
    if ( $stop_operation ) {
        echo '<div id="operation-status">Operazione interrotta.</div>';
    } else {
        echo '<div id="operation-status">Operazione in corso.</div>';
    }
}
}

?>
