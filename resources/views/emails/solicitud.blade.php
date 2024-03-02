<p>L'usuari {{$product->user->username}} ha creat un producte nou pendent de validació:</p>

<p><a href="{{action('ProductController@edit',$product->id)}}">{{$product->title_ca}}</a></p>