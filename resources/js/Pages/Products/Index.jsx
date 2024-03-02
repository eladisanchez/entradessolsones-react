import { Head } from '@inertiajs/react'

export default function Product({ products }) {
  return (
    <>
      <Head title="Hola!" />
      <h1>Hola</h1>
      <ul>
      {products['activities'].map(category=>(
        <section className="category">
        <h3>{category.titol_ca}</h3>
        {category.products.map(product=>(
          <>
            <h4>{product.titol_ca}</h4>
          </>
        ))}
        </section>
      ))}
      </ul>
      {JSON.stringify(products['activities'])}
    </>
  )
}