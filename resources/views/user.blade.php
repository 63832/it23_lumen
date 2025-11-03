<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Användare</title>
        <style>
            label {
                display: block;
                margin-bottom: 10px;
            }
        </style>
    </head>
    <body>
        <h1>Användare</h1>
        @if($me->admin)
            <form method="post">
                @if(isset($user))
                    <input type="hidden" name="id" value="{{$user->id}}">
                @endif
                <label>Namn:
                    <input name="namn" required placeholder="Ange namn" value="{{$user->namn ?? ''}}">
                </label>
                <label>Epost:
                    <input type="email" name="epost" required placeholder="Ange epost" value="{{$user->epost ?? ''}}">
                </label>
                <label>Lösenord:
                    <input type="password" name="losenord" placeholder="Ange lösenord">
                </label>
                @if(isset($user))
                    <label>
                        <input type="hidden" name="admin" value="0">
                        <input type="checkbox" name="admin" value="1"
                            {{$user->admin ? 'checked' : ''}}> Administratör
                    </label>
                @endif
                <input type="submit" value="Spara">
                <input type="reset" value="Ångra">
                @if(isset($user))
                    <input type="submit" name="delete" value="Ta bort">
                @endif
            </form>
        @endif
        @if (!empty($lista))
            <h2>Användarlista</h2>
            <ul>
                @foreach($lista as $u)
                    <li>
                        {{$u->id}}.
                        @if($me->admin)
                            <a href="/anvandare/{{$u->id}}">{{$u->namn}}</a>
                        @else
                            {{$u->namn}}
                        @endif
                        {{$u->epost}}
                    </li>
                @endforeach
            </ul>
        @endif
    </body>
</html>
