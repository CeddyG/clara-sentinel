<body>
    <p><strong>Bonjour,</strong></p>
    
    <p>
        Pour réinitialiser votre mot de passe, cliquez sur le lien suivant :
        {!! link_to_route('password.reset', route('password.reset', ['token' => $oEvent->oReminder->code]), ['token' => $oEvent->oReminder->code]) !!}
    </p>
    
</body>