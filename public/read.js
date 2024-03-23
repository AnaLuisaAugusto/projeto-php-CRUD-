const cliente_Read = document.getElementById("cliente_Read");
const tabelaClientes = document.getElementById("tabelaClientes");

async function read(urli) 
{    
    try 
    {
        const response = await fetch(urli, 
        {
            method: 'GET',
            headers: { 'Content-Type': 'application/json' }
        });

        if (!response.ok) 
        {
            throw new Error('Erro na requisição');
        }

        const data = await response.json();

        if (data.length > 0) {

            tabelaClientes.innerHTML = "";

            data.forEach(
            cliente => 
            {
                const tr = document.createElement("tr");
                tr.innerHTML = `
                    <td>${cliente.cliente_id}</td>
                    <td>${cliente.nome}</td>
                    <td>${cliente.email}</td>
                    <td>${cliente.cidade}</td>
                    <td>${cliente.estado}</td>
                `;
                tabelaClientes.appendChild(tr);
            });
        } 
        else 
        {
            cliente_Read.innerText = "Nenhum cliente foi encontrado";
            tabelaClientes.innerHTML = ""; 
        }
    } 
    catch (error) 
    {
        console.error('Falha ao enviar requisição:', error);
    }
}