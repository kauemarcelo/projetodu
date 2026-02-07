<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Horário Espiritual</title>

<style>
body{
  margin:0;
  font-family:Arial, sans-serif;
  background:#f0f0f0;
}
.header{
  background:#ffffffcc;
  padding:10px;
  text-align:center;
  font-size:20px;
  font-weight:bold;
}
.top-controls{
  background:#ffffffdd;
  margin:10px;
  padding:8px;
  border-radius:6px;
  text-align:center;
}
.container{
  background:#ffffffdd;
  margin:10px;
  padding:10px;
  border-radius:6px;
  overflow-x:auto;
}
table{ border-collapse:collapse; }
th,td{
  border:1px solid #999;
  padding:3px;
  font-size:12px;
  text-align:center;
}
.acao-input{
  width:95%;
  border:none;
  background:transparent;
  font-weight:bold;
}
.dia{
  width:24px;
  height:24px;
  cursor:pointer;
  background:#eee;
}
.verde{ background:#4CAF50; }
.amarelo{ background:#FFC107; }
.vermelho{ background:#F44336; }
button{ cursor:pointer; margin-left:5px; }

/* MODAL */
.modal{
  position:fixed;
  inset:0;
  background:rgba(0,0,0,.4);
  display:none;
  align-items:center;
  justify-content:center;
}
.modal-box{
  background:#fff;
  padding:15px;
  border-radius:6px;
  text-align:center;
}
.modal-box button{
  margin:5px;
  padding:8px 12px;
  cursor:pointer;
}
</style>
</head>

<body>

<div class="header">HORÁRIO ESPIRITUAL</div>

<div class="top-controls">
Pessoa:
<select id="pessoa" onchange="carregar()">
  <option value="eduardo">Eduardo</option>
  <option value="alessandra">Alessandra</option>
</select>

Mês:
<select id="mes" onchange="carregar()">
<script>
["Janeiro","Fevereiro","Março","Abril","Maio","Junho",
 "Julho","Agosto","Setembro","Outubro","Novembro","Dezembro"]
.forEach((m,i)=>document.write(`<option value="${i+1}">${m}</option>`));
</script>
</select>

Ano:
<select id="ano" onchange="carregar()"></select>

<button onclick="salvar()">💾 Salvar</button>
</div>

<div class="container">
<table>
<thead>
<tr>
<th>Ação</th>
<script>
for(let i=1;i<=31;i++) document.write(`<th>${i}</th>`);
</script>
</tr>
</thead>

<tbody id="corpo"></tbody>
</table>
</div>

<!-- MODAL STATUS -->
<div class="modal" id="modal">
  <div class="modal-box">
    <p>Status do dia</p>

    <button style="background:#4CAF50;color:#fff"
            onclick="setStatus('verde')">Feito</button>

    <button style="background:#FFC107"
            onclick="setStatus('amarelo')">Parcial</button>

    <button style="background:#F44336;color:#fff"
            onclick="setStatus('vermelho')">Não concluído</button>

    <br>
    <button onclick="setStatus('')">Limpar</button>
  </div>
</div>

<script>
const corpo = document.getElementById("corpo");
const modal = document.getElementById("modal");
let celulaAtual = null;

/* ANO */
const anoAtual = new Date().getFullYear();
for(let a=anoAtual;a<=anoAtual+5;a++){
  ano.innerHTML += `<option>${a}</option>`;
}

/* CRIAR LINHA */
function criarLinha(acao="", dias=[]){
  const tr = document.createElement("tr");
  let html = `<td><input class="acao-input" value="${acao}"></td>`;
  for(let i=0;i<31;i++){
    html += `<td class="dia ${dias[i]||""}"></td>`;
  }
  tr.innerHTML = html;
  corpo.appendChild(tr);

  tr.querySelectorAll(".dia").forEach(td=>{
    td.onclick=()=>{
      celulaAtual = td;
      modal.style.display="flex";
    };
  });
}

/* DEFINIR STATUS */
function setStatus(status){
  celulaAtual.className = "dia " + status;
  modal.style.display="none";
}

/* SALVAR */
function salvar(){
  const dados=[];
  document.querySelectorAll("#corpo tr").forEach(tr=>{
    const acao = tr.querySelector("input").value;
    const dias=[];
    tr.querySelectorAll(".dia").forEach(td=>{
      dias.push(
        td.classList.contains("verde")?"verde":
        td.classList.contains("amarelo")?"amarelo":
        td.classList.contains("vermelho")?"vermelho":""
      );
    });
    dados.push({acao,dias});
  });

  fetch("salvar.php",{
    method:"POST",
    headers:{ "Content-Type":"application/json" },
    body: JSON.stringify({
      pessoa:pessoa.value,
      mes:mes.value,
      ano:ano.value,
      dados
    })
  }).then(r=>r.text()).then(alert);
}

/* CARREGAR */
function carregar(){
  corpo.innerHTML="";
  fetch(`carregar.php?pessoa=${pessoa.value}&mes=${mes.value}&ano=${ano.value}`)
    .then(r=>r.json())
    .then(d=>{
      if(d.length){
        d.forEach(l=>criarLinha(l.acao,l.dias));
      }else{
        for(let i=0;i<4;i++) criarLinha();
      }
    });
}

carregar();
</script>

</body>
</html>
