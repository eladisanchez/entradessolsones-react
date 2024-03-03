import { Head, Link } from "@inertiajs/react";

export default function Product({ products }) {
    return (
        <>
            <Head title="Entrades Solsonès" />
            <div className="container">
                {products["activities"].map((category) => (
                    <section className="category">
                        <h3>{category.title}</h3>
                        <ul>
                            {category.products.map((product) => (
                                <li>
                                    <Link href={"/activitat/" + product.name}>
                                        <h4>{product.title}</h4>
                                    </Link>
                                </li>
                            ))}
                        </ul>
                    </section>
                ))}
            </div>
        </>
    );
}
