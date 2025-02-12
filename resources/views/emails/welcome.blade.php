@component('mail::message')

<img src="{{ asset('images/white-logo.png') }}" alt="PirasWallet Logo" width="150" style="display: block; margin: 0 auto 20px;">

# Welcome to PirasWallet! 🎉  

Hello **{{ $name }}**,  

Your new **digital wallet** is here! With **PirasWallet**, managing your finances has never been easier. Enjoy a seamless experience with secure transactions, real-time balance tracking, and a smooth interface designed for both web and mobile.  

Whether you're making transfers, handling deposits, or reviewing your transaction history, everything is just a few clicks away.  

@component('mail::button', ['url' => '#'])
Access Your Wallet
@endcomponent

If you have any questions, we're here to help.  

Enjoy your journey with **PirasWallet**! 💸  

Best regards,  
**The PirasWallet Team**  
@endcomponent
