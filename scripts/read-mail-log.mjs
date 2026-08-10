import fs from 'node:fs';
import path from 'node:path';
import { fileURLToPath } from 'node:url';
import SftpClient from 'ssh2-sftp-client';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const root = path.resolve(__dirname, '..');
const sftpConfig = JSON.parse(fs.readFileSync(path.join(root, '.vscode', 'sftp.json'), 'utf8'));
const domain = sftpConfig.remotePath.includes('kmxmedia') ? 'kmxmedia.com' : 'starglassdigital.com';
const logPath = `/home/u467937533/domains/${domain}/private/logs/mail.log`;

const sftp = new SftpClient();
try {
  await sftp.connect({
    host: sftpConfig.host,
    port: sftpConfig.port,
    username: sftpConfig.username,
    password: sftpConfig.password,
  });
  const exists = await sftp.exists(logPath);
  console.log('log_path:', logPath);
  console.log('exists:', exists);
  if (exists) {
    const buf = await sftp.get(logPath);
    console.log(buf.toString('utf8'));
  }
} finally {
  await sftp.end();
}