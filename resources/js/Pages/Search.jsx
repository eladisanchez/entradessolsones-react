import { Head } from "@inertiajs/react";
import { Heading, Container, Grid, Spacer } from "@/components/atoms";
import { Thumbnail } from "@/components/molecules";

const Search = ({ products, keyword }) => {
  return (
    <>
      <Head title={`${keyword} - Entrades Solsonès`} />
      <Spacer top={12} />
      <Container>
        <Heading tag="h2" size={2} center={true} spacerTop={6} spacerBottom={6}>
          Resultats de la cerca "{keyword}"
        </Heading>
        <Grid>
          {products.map((product) => (
            <Thumbnail key={product.id} product={product} />
          ))}
        </Grid>
        <Spacer top={12} />
      </Container>
    </>
  );
};

export default Search;
