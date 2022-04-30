import fs from 'fs/promises';
import path from 'path';
import { fileURLToPath } from 'url';

export async function getTopics() {
  const __filename = fileURLToPath(import.meta.url);
  const __dirname = path.dirname(__filename);
  const filePath = path.join(__dirname, '/../files/topics.json');
  const topicsRaw = await fs.readFile(filePath, 'utf8');
  return JSON.parse(topicsRaw);
}

export function writeResults(results) {
  const __filename = fileURLToPath(import.meta.url);
  const __dirname = path.dirname(__filename);
  const filePath = path.join(__dirname, '/../files/results.txt');
  const string = results
    .map((result) => `${result.topic}, ${result.occurrence}`)
    .join('\n');
  return fs.writeFile(filePath, string);
}
