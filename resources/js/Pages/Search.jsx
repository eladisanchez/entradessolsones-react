import { Head } from "@inertiajs/react";
import { Heading, Container, Grid } from "@/components/atoms";
import { Thumbnail } from "@/components/molecules";

const Search = ({ products, keyword }) => {
  return (
    <>
      <Head title="Entrades Solsonès" />
      <Container>
        <Heading tag="h2" size={2} center={true} spacerTop={6} spacerBottom={6}>
          Resultats de la cerca "{keyword}"
        </Heading>
        <Grid>
          {products.map((product) => (
            <Thumbnail key={product.id} product={product} />
          ))}
        </Grid>
      </Container>
    </>
  );
};

export default Search;
