import { Head } from "@inertiajs/react";

export default function Product({ product }) {
    return (
        <>
            <Head title="Hola!" />
            <h1>{product.title}</h1>
            <div
                dangerouslySetInnerHTML={{ __html: product.description }}
            ></div>
        </>
    );
}
