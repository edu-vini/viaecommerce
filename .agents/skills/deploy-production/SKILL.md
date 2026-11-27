---
name: deploy-production
description: >-
  Use this skill when the user asks to deploy to production, sync the production
  branch, commit current workspace changes to the homolog branch, merge homolog
  into production, and push production to the remote repository.
---

# Deploy Production (ViaEcommerce)

Procedimento padrão deste projeto para sincronizar a branch `production`, commitar todas as alterações do contexto atual na branch `homolog`, realizar o merge para `production` e subir (`push`) a `production`.

## Pré-requisitos e Segurança

- **Nunca** force push (`--force`) em `production` ou `homolog`.
- **Nunca** ignore conflitos de merge: se houver conflito em qualquer etapa, pare imediatamente, informe os arquivos em conflito e resolva antes de prosseguir.
- **Nunca** exponha ou imprima o valor de `GITHUB_PAT` em logs, commits ou mensagens.
- Verifique se o remote `origin` está configurado (`git remote -v`). Caso não esteja, informe o usuário e pare.
- Carregue `GITHUB_PAT` do arquivo `.env` (ignorado pelo `.gitignore`) antes de executar comandos remotos (`fetch`, `pull`, `push`):
  ```powershell
  $pat = (Get-Content .env | Where-Object { $_ -match '^GITHUB_PAT=' } | ForEach-Object { ($_ -split '=', 2)[1].Trim() })
  if (-not $pat) { throw "GITHUB_PAT não encontrado no .env" }
  ```
  Use `-c url."https://x-access-token:$pat@github.com/".insteadOf="https://github.com/"` nos comandos `git fetch`, `git pull` e `git push` para autenticar sem gravar o token em `.git/config`.

## Passo a Passo

### 1. Inspecionar o contexto e proteger alterações locais

1. Execute `git status` e `git remote -v` para verificar a branch atual, o remote `origin` e as alterações pendentes (modificadas, staged ou untracked).
2. Se houver alterações não commitadas (modified ou untracked), salve-as temporariamente com stash incluindo arquivos untracked:
   ```powershell
   git stash push -u -m "deploy-production: alterações do contexto"
   ```
   *(Anote que um stash foi criado para restaurá-lo na branch `homolog` no Passo 3.)*

### 2. Sincronizar a branch `production`

1. Busque as referências atualizadas do remoto usando o PAT do `.env`:
   ```powershell
   git -c url."https://x-access-token:$pat@github.com/".insteadOf="https://github.com/" fetch origin
   ```
2. Faça checkout na branch `production` (criando o tracking local se ainda não existir) e atualize-a com o remoto:
   ```powershell
   git checkout production; if ($LASTEXITCODE -ne 0) { git checkout -b production origin/production }
   git -c url."https://x-access-token:$pat@github.com/".insteadOf="https://github.com/" pull origin production
   ```

### 3. Commitar todas as alterações do contexto na branch `homolog`

1. Mude para a branch `homolog` (ou crie a partir de `origin/homolog`, ou de `production` caso `homolog` ainda não exista):
   ```powershell
   git checkout homolog; if ($LASTEXITCODE -ne 0) { git checkout -b homolog origin/homolog; if ($LASTEXITCODE -ne 0) { git checkout -b homolog } }
   ```
   Se `origin/homolog` existir, sincronize antes:
   ```powershell
   git -c url."https://x-access-token:$pat@github.com/".insteadOf="https://github.com/" pull origin homolog
   ```
2. Se um stash foi criado no Passo 1, restaure as alterações do contexto:
   ```powershell
   git stash pop
   ```
   - Se houver conflito ao aplicar o stash, resolva antes de continuar (não descarte o stash até confirmar que tudo foi aplicado corretamente).
3. Verifique os arquivos alterados com `git status` para garantir que nenhum arquivo sensível (como `.env`) está sendo incluído.
4. Adicione todas as alterações do contexto e crie o commit na branch `homolog` com uma mensagem descritiva (ex.: Conventional Commits resumindo o que foi alterado no contexto):
   ```powershell
   git add -A
   git commit -m "<tipo>: <descrição concisa das alterações>"
   ```
   *(Se não houver nada para commitar em `homolog` além de commits já existentes nela, siga para o próximo passo.)*

### 4. Fazer merge de `homolog` em `production`

1. Volte para a branch `production`:
   ```powershell
   git checkout production
   ```
2. Faça o merge da branch `homolog` na `production`:
   ```powershell
   git merge homolog
   ```
   - Se houver conflitos de merge, pare e ajude o usuário a resolvê-los antes de continuar.

### 5. Subir a branch `production`

1. Envie a branch `production` atualizada para o remoto usando o PAT do `.env`:
   ```powershell
   git -c url."https://x-access-token:$pat@github.com/".insteadOf="https://github.com/" push origin production
   ```
2. *(Opcional, se a branch `homolog` também precisar ficar sincronizada no remoto)*:
   ```powershell
   git -c url."https://x-access-token:$pat@github.com/".insteadOf="https://github.com/" push origin homolog
   ```

## Validação Final

Execute para confirmar que tudo foi sincronizado, mergeado e enviado corretamente:
```bash
git status
git log -n 3 --oneline --decorate
```
Confirme que:
- A working tree está limpa (`nothing to commit, working tree clean`).
- `production` está alinhada com `origin/production` e contém o commit de `homolog`.
