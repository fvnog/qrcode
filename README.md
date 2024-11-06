
# 📲 Gerador de QR Code - Fernando Nogueira

Gere QR Codes de forma rápida e fácil com esta ferramenta intuitiva! 🚀 Ideal para todas as suas necessidades de criação de QR Codes personalizados.

## 📜 Descrição

Este gerador de QR Code foi criado para oferecer uma experiência prática e eficiente, permitindo a criação de códigos QR personalizados com opções de cor, plano de fundo, e até mesmo com logotipos! Desenvolvido com PHP, utilizando a biblioteca [Endroid QrCode](https://github.com/endroid/qr-code) para garantir alta qualidade.

## 🎨 Funcionalidades

- **Geração de QR Codes personalizados** com cores de fundo e de primeiro plano definidas pelo usuário.
- **Upload de logotipos** para incorporar uma imagem central no QR Code.
- **Download fácil** do QR Code gerado em formato PNG.
- **Histórico de QR Codes** gerados durante a sessão atual.

## ⚙️ Instalação

1. Clone o repositório:
   ```bash
   git clone https://github.com/seuusuario/gerador-qrcode.git
   ```
2. Instale as dependências do projeto:
   ```bash
   cd gerador-qrcode
   composer install
   ```
3. Certifique-se de que o diretório `uploaded_logos/` tem permissão de escrita:
   ```bash
   chmod 755 uploaded_logos/
   ```
4. Execute em um servidor local (ex: [XAMPP](https://www.apachefriends.org/), [Laragon](https://laragon.org/)) e acesse a aplicação pelo navegador.

## 🚀 Como Usar

1. Acesse o gerador de QR Code pelo navegador.
2. Insira o link que deseja transformar em QR Code.
3. Se desejar, personalize as cores de **primeiro plano** e **fundo**.
4. Opcionalmente, faça o upload de um logotipo para incluir no QR Code.
5. Clique em **Gerar** para visualizar e baixar o QR Code.
6. Para limpar o histórico de QR Codes gerados, clique em **Limpar Tudo**.

## 🛠 Tecnologias Utilizadas

- **PHP** com [Endroid QrCode](https://github.com/endroid/qr-code)
- **Bootstrap** para estilização
- **JavaScript (jQuery)** para manipulação de elementos e requisições
- **FontAwesome** para ícones

## 📄 Meta Tags e SEO

Este projeto vem pré-configurado com meta tags para melhorar a indexação e o compartilhamento em redes sociais. Basta substituir os valores como `URL_da_imagem_de_previsao` e `URL_da_pagina` nas meta tags no arquivo HTML.

## 🤝 Contribuições

Contribuições são sempre bem-vindas! Sinta-se à vontade para abrir uma issue ou fazer um fork do projeto e enviar um pull request. 

## 🖼️ Imagem de Exemplo


![Exemplo de QR Code](https://i.imgur.com/MDKVFF4.png)
![Exemplo de QR Code](https://i.imgur.com/llbbxxj.png)

---

Aproveite o gerador de QR Code e compartilhe o projeto se achar útil! 😃
