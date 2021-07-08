<div class="container">
    <form>
        <div class="mb-3">
            <label for="msg" class="form-label">Message</label>
            <input type="text" name="msg" id="msg" placeholder="Message" class="form-control">
        </div>

        <button class="btn btn-primary">Submit</button>
    </form>

    <button id="disconnect">Disconnect</button>
</div>

<script>
    const conn = new WebSocket('ws://localhost:8080');

    conn.onopen = () => {
        console.log('Connection established');
    };
    conn.onclose = () => {
        console.log('Connection closed');
    }

    conn.onmessage = (e) => {
        console.log(e)
        let json = JSON.parse(e.data);
        let el = document.createElement('p');
        el.innerHTML = json.message;

        document.querySelector('body').appendChild(el);
    };

    const submitButton = document.querySelector('form button');
    const msg = document.querySelector('#msg');
    const disconnect = document.querySelector('#disconnect');

    disconnect.addEventListener('click', (e) => {
        e.preventDefault();
        conn.close()
    })

    submitButton.addEventListener('click', (e) => {
        e.preventDefault();
        let data = {
            id:'Julien',
            message : msg.value
        }
        console.log('clic')
        conn.send(JSON.stringify(data));
        msg.value = '';
    })

</script>