import { ApolloServer } from '@apollo/server';
import { startStandaloneServer } from '@apollo/server/standalone';
import { MongoClient } from 'mongodb';

const port = Number.parseInt(process.env.GRAPHQL_PORT ?? '4000', 10);
const mongoUri = process.env.MONGODB_URI ?? 'mongodb://mongo:27017';

const typeDefs = `#graphql
  type Query {
    hello: String!
    mongoStatus: String!
  }
`;

const resolvers = {
  Query: {
    hello: () => 'GraphQL is running',
    mongoStatus: async () => {
      const client = new MongoClient(mongoUri);

      try {
        await client.connect();
        await client.db('admin').command({ ping: 1 });

        return 'MongoDB connection is healthy';
      } finally {
        await client.close();
      }
    },
  },
};

const server = new ApolloServer({ typeDefs, resolvers });

startStandaloneServer(server, {
  listen: { port },
}).then(({ url }) => {
  // eslint-disable-next-line no-console
  console.log(`GraphQL server ready at ${url}`);
});
