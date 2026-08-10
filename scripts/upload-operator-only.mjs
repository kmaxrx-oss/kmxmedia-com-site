import fs from 'node:fs';
import path from 'node:path';
import { fileURLToPath } from 'node:url';
import SftpClient from 'ssh2-sftp-client';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const root = path.resolve(__dirname, '..');
const sftpConfig = JSON.parse(fs.readFileSync(path.join(root, '.vscode', 'sftp.json'), 'utf8'));
const localOperator = path.join(root, 'public', 'operator');
const remoteOperator = `${sftpConfig.remotePath}/operator`;

async function uploadDir(sftp, local, remote) {
  await sftp.mkdir(remote, true);
  for (const entry of fs.readdirSync(local, { withFileTypes: true })) {
    const localPath = path.join(local, entry.name);
    const remotePath = `${remote}/${entry.name}`;
    if (entry.isDirectory()) {
      await uploadDir(sftp, localPath, remotePath);
    } else {
      await sftp.put(localPath, remotePath);
      console.log(`UPLOADED ${entry.name} -> ${remotePath}`);
    }
  }
}

const sftp = new SftpClient();
try {
  await sftp.connect({
    host: sftpConfig.host,
    port: sftpConfig.port,
    username: sftpConfig.username,
    password: sftpConfig.password,
  });
  await uploadDir(sftp, localOperator, remoteOperator);
  const robotsLocal = path.join(root, 'public', 'robots.txt');
  await sftp.put(robotsLocal, `${sftpConfig.remotePath}/robots.txt`);
  console.log('UPLOADED robots.txt');
  console.log('Operator upload complete.');
} finally {
  await sftp.end();
}