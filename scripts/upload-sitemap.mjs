import fs from 'node:fs';
import path from 'node:path';
import { fileURLToPath } from 'node:url';
import SftpClient from 'ssh2-sftp-client';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const root = path.resolve(__dirname, '..');
const sftpConfig = JSON.parse(fs.readFileSync(path.join(root, '.vscode', 'sftp.json'), 'utf8'));
const localFile = path.join(root, 'dist', 'sitemap.xml');
const remoteFile = `${sftpConfig.remotePath}/sitemap.xml`;

const sftp = new SftpClient();
try {
  await sftp.connect({
    host: sftpConfig.host,
    port: sftpConfig.port,
    username: sftpConfig.username,
    password: sftpConfig.password,
  });
  await sftp.put(localFile, remoteFile);
  console.log(`UPLOADED sitemap.xml -> ${remoteFile}`);
} finally {
  await sftp.end();
}