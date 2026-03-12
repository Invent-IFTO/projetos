<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Convite para Projeto</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }
        .content {
            padding: 30px;
            line-height: 1.6;
            color: #333;
        }
        .project-info {
            background-color: #f8f9fa;
            border-left: 4px solid #007bff;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .project-info h3 {
            margin-top: 0;
            color: #007bff;
            font-size: 18px;
        }
        .role-info {
            background-color: #e9ecef;
            padding: 10px;
            border-radius: 4px;
            margin: 15px 0;
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .accept-button {
            display: inline-block;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            padding: 15px 30px;
            border-radius: 5px;
            font-weight: bold;
            font-size: 16px;
        }
        .accept-button:hover {
            background-color: #218838;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #e9ecef;
            font-size: 14px;
            color: #6c757d;
        }
        .expiration-notice {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 10px;
            border-radius: 4px;
            margin: 15px 0;
            font-size: 14px;
        }
        .info-table {
            width: 100%;
            margin: 15px 0;
        }
        .info-table td {
            padding: 5px 0;
            vertical-align: top;
        }
        .info-table td:first-child {
            font-weight: bold;
            width: 120px;
            color: #495057;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>🎯 Convite para Projeto</h1>
        </div>
        
        <div class="content">
            <p>Olá!</p>
            
            <p>Você foi convidado(a) por <strong>{{ $responsavel->name }}</strong> para participar do seguinte projeto:</p>
            
            <div class="project-info">
                <h3>{{ $projeto->titulo }}</h3>
                @if($projeto->descricao)
                    <p>{{ $projeto->descricao }}</p>
                @endif
                
                <table class="info-table">
                    <tr>
                        <td>Equipe:</td>
                        <td>{{ $equipe->descricao }}</td>
                    </tr>
                    <tr>
                        <td>Data de Início:</td>
                        <td>{{ $projeto->data_inicio ?? 'A definir' }}</td>
                    </tr>
                    <tr>
                        <td>Data de Fim:</td>
                        <td>{{ $projeto->data_fim ?? 'A definir' }}</td>
                    </tr>
                </table>
            </div>

            @if($convite->lider_equipe || $convite->gerente_projeto)
                <div class="role-info">
                    <strong>Funções atribuídas:</strong>
                    <ul style="margin: 5px 0;">
                        @if($convite->lider_equipe)
                            <li>👨‍💼 <strong>Líder de Equipe</strong></li>
                        @endif
                        @if($convite->gerente_projeto)
                            <li>🎯 <strong>Gerente de Projeto</strong></li>
                        @endif
                    </ul>
                </div>
            @endif

            <div class="expiration-notice">
                <strong>⏰ Atenção:</strong> Este convite expira em <strong>{{ $convite->expires_at->format('d/m/Y às H:i') }}</strong>
            </div>

            <p>Para aceitar o convite e fazer parte do projeto, clique no botão abaixo:</p>

            <div class="button-container">
                <a href="{{ $linkConvite }}" class="accept-button">
                    ✅ Aceitar Convite
                </a>
            </div>

            <p>Caso não consiga clicar no botão, copie e cole este link no seu navegador:</p>
            <p style="word-break: break-all; color: #007bff; font-size: 14px;">{{ $linkConvite }}</p>

            <p>Se você não deseja participar deste projeto, pode ignorar este e-mail.</p>

            <hr style="border: none; border-top: 1px solid #e9ecef; margin: 30px 0;">

            <p style="font-size: 14px; color: #6c757d;">
                <strong>Convidado por:</strong> {{ $responsavel->name }} ({{ $responsavel->email }})<br>
                <strong>Enviado em:</strong> {{ now()->format('d/m/Y às H:i') }}
            </p>
        </div>
        
        <div class="footer">
            <p>Sistema de Gestão de Projetos - IF Projetos</p>
            <p>Este é um e-mail automático, não responda diretamente.</p>
        </div>
    </div>
</body>
</html>