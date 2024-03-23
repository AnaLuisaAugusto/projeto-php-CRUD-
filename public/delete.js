const cliente_Delete = document.getElementById("cliente_Delete");
const btn_delete = document.getElementById("btn_delete");

async function deletee(urli) 
{    
    try 
    {
        const response = await fetch(urli, 
        {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/json' }
        });

        if (!response.ok) 
        {
            throw new Error('Erro na requisição');
        }

        const data = await response.json();

        if (data) 
        {
            cliente_Delete.innerText = "Cliente excluido com sucesso!";
        } 
        else 
        {
            cliente_Delete.innerText = "Nenhum cliente foi encontrado";
        }
    } 
    catch (error) 
    {
        console.error('Falha ao enviar requisição:', error);
    }
}
