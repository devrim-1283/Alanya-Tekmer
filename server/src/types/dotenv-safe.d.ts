declare module 'dotenv-safe' {
  export function config(options?: {
    path?: string;
    example?: string;
    allowEmptyValues?: boolean;
  }): void;
}

