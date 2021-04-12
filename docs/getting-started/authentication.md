# Autenticação

Necessário possuir credenciais de acesso para o ambiente sandbox e produção.

As credenciais são: *MerchantId* e *MerchantKey*.

 > Solicite as credenciais através do site https://suporte.braspag.com.br ou envie um e-mail para comercial@braspag.com.br para mais informações 

**Sandbox**

```php
require 'vendor/autoload.php';

use Braspag\Gateway\Domains\Environment;

$env = Environment::sandbox('merchant-id', 'merchant-key');
```

**Production**

```php
require 'vendor/autoload.php';

use Braspag\Gateway\Domains\Environment;

$env = Environment::production('merchant-id', 'merchant-key');
```