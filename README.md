# Request Model Parser
php 는 타입에 대한 자유도가 높다.   
이는 장점이 될 수 있지만 IDE 자동완성, 개발자의 실수 가능성 등의 측면에서 단점이 될 수 있다.   
이 단점이 request parameter 에서 가장 크게 작용 한다고 생각한다.   
개박자가 입력하지 않는 Argument 을 활용해야 한다.

이를 개선하기 위해 PHP 8.0부터 지원하는 Attribute 를 이용하여   
.NET, Spring Boot 등에서 지원하는 Controller method 에 argument parser 와 비슷하게 사용 할 수 있는   
Laravel 용 패키지이다. (추후에 패키지로 작성 예정)

## Usage
```PHP
<?php
...
use App\Attributes\FromQuery;
use App\Attributes\FromHeader;

class ExampleController extends Controller
{
    public function index(#[FromQuery('page')] int $page, #[FromHeader('content-type')] string $contentType)
    {
        ...
    }
}
```

### With Dto class
```PHP
<?php
...
use App\Attributes\FromBody;

class ExampleController extends Controller
{
    public function index(#[FromBody] BodyDto $dto)
    {
        ...
    }
}

class BodyDto
{
    public readonly int $page;
    #[FromQuery(field: 'field_name')]
    public readonly int $propertyName; // parameter 이름과 propery 이름이 다른 경우
}
```

### With Dto class (여러가지 파라미터 출처 혼합)
```PHP
<?php
...
use App\Attributes\FromQuery;
use App\Attributes\FromRequest;
use App\Attributes\FromHeader;
use App\Attributes\FromBody;

class ExampleController extends Controller
{
    public function index(#[FromRequest] RequestDto $dto)
    {
        ...
    }
}

class RequestDto
{
    #[FromQuery]
    public readonly QueryDto $query;
    
    #[FromHeader('content-type')]
    public readonly string $contentType;
    
    #[fromBody]
    public readonly BodyDto $body;
}

class QueryDto
{
    public readonly int $page;
}

class BodyDto
{
    public readonly string $name;
}
```

## object collect parse
php 는 array 요소의 타입을 강제할 수 없다.   
때문에 builtin 이 아닌 타입읠 배열 구조의 요청을 처리하는경우   
Attribute 로 배열 요소의 타입을 입력시 해당 타입으로 파싱된다.
```PHP
<?php
...
use App\Attributes\FromBody;

class ExampleController extends Controller
{
    public function index(#[FromBody] BodyDto $dto)
    {
        ...
    }
}

class BodyDto
{
    /**
     * @var UserDto[] $users
     */
    #[Collect(UserDto::class)]
    public readonly array $users;
}

class UserDto
{
    #[FromBody('user_name')]
    public readonly string $name;
}
```

### validation
기본 파라미터는 누락시 Laravel validator 를 활용하여 required 유효성 검사에서 필터링됩니다.   
required 필터링을 원하지 않으면 null 을 허용하거나 default value 를 추가하면 됩니다.
```php
class QueryDto
{
    #[Validate('min:1')]
    public readonly int $page;
    
    #[Validate(new CustomRule())]
    public readonly string $name;
    
    public readonly ?string $group; // without required
    public readonly int $perPage = 1; // without required
}
```
